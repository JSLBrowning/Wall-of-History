from datetime import timedelta
from deep_translator import GoogleTranslator
import whisper
import os


def raw_text_transcription(audio_file):
    print("Transcribing " + audio_file + ".")

    # Load the model and transcribe the audio.
    model = whisper.load_model("medium")
    result = model.transcribe(audio_file, fp16=False)

    # extract the text and language information
    language = result["language"]
    text = result["text"]
    segments = result["segments"]

    # Write language and text info to output file.
    text_file = os.path.splitext(audio_file)[0] + ".txt"
    with open(text_file, "w", encoding='utf8') as f:
        f.write(f"Language: {language}\nText:\n{text}")

    print("Finished transcribing " + audio_file + ".")

    print("Creating subtitles...")

    sub_file = os.path.splitext(audio_file)[0] + "_" + language + ".srt"
    sub_file_en = os.path.splitext(audio_file)[0] + "_en.srt"
    for segment in segments:
        print(segment['text'])
        start_time = str(0) + str(timedelta(seconds=int(segment['start']))) + ',000'
        end_time = str(0) + str(timedelta(seconds=int(segment['end']))) + ',000'
        segment_text = segment['text']
        try:
            segment_text_translated = GoogleTranslator(source='auto', target='english').translate(text=segment_text)
        except Exception:
            segment_text_translated = ''
        segment_id = segment['id'] + 1
        # segment = f"{segment_id}\n{start_time} --> {end_time}\n{segment_text}\n\n"
        if segment_text != "ん":
            segment = f"{segment_id}\n{start_time} --> {end_time}\n{segment_text[1:] if segment_text[0] == ' ' else segment_text}"
            segment_translated = f"{segment_id}\n{start_time} --> {end_time}\n{segment_text_translated[1:] if segment_text_translated[0] == ' ' else segment_text_translated}"
            with open(sub_file, "a", encoding='utf8') as f2:
                f2.write(segment + "\n\n")
            with open(sub_file_en, "a", encoding='utf8') as f3:
                f3.write(segment_translated + "\n\n")


raw_text_transcription("in/audio2.mp3")
