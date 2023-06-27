from pytube import YouTube


def download_yt(url):
    yt = YouTube(url)
    # Get highest-quality video and save as an MP4.
    yt.streams.filter(only_audio=False, progressive=False, file_extension="mp4") \
        .order_by("resolution").desc().first().download(output_path="out/yt/", filename=yt.title + "_video.mp4")
    # Get highest-quality audio and save as an MP3.
    yt.streams.get_audio_only().download(output_path="out/yt/", filename=yt.title + "_audio.mp3")


def main(url_list):
    for url in url_list:
        download_yt(url)


main(["https://www.youtube.com/watch?v=cWXLqWiczs0"])
