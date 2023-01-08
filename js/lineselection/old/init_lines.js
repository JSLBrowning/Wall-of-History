import $ from 'jquery';

import RangeSelector from './range_selector';

const LINE_PAT = /^post(\d+)-line(\d+(?:-\d+)?(?:,\d+(?:-\d+)?)*)$/;

export default function initLines({
  scrollPadding,
  toggleSpoiler,
  spoilerSelector,
  containerSelector,
  lineSelector
}) {

  let $currPost = null;
  let selector = null;
  let currPostNo = null;
  let initial = true;
  let clicked = false;

  function deselectPost() {
    if($currPost != null) {
      $currPost.removeClass('x-target');
    }
  }

  function deselectLines() {
    if(selector != null) {
      selector.deselect();
    }
  }

  function setSelection({ $post = null, postNo = null }) {
    if($post != null) {
      deselectPost();
      deselectLines();
      $post.addClass('x-target');
      $currPost = $post;
      if(selector != null) {
        selector.deselect();
      }
      selector = null;
      currPostNo = null;
    } else if(postNo != null) {
      deselectPost();
      $currPost = null;
      if(postNo !== currPostNo) {
        deselectLines();
        selector = new RangeSelector();
        selector.on('toggle', function(ranges, select) {
          const els = [];
          for(let [lo, hi] of ranges) {
            for(let i = lo; i <= hi; ++i) {
              els.push(getLineEl(postNo, i));
            }
          }
          $(els).toggleClass('x-target', select);
        });
        selector.on('ranges', function(ranges) {
          const newHash = rangesToHash(postNo, ranges);
          if(clicked) {
            scrollPadding.setHash(newHash, { push: initial });
            initial = false;
            clicked = false;
          } else {
            // If the hashes are different, this means that a non-normalized
            // URL was typed in and is being rewritten.
            scrollPadding.setHash(newHash, { push: false });
            // Make sure to open all enclosing spoiler tags.
            const $lines = $(rangesToLineEls(postNo, ranges));
            toggleSpoiler($lines.parents(spoilerSelector), true);
            // Scroll to the first selected line.
            if($lines.length > 0) {
              scrollPadding.scrollToElement($lines.get(0));
            }
            initial = true;
          }
        });
        currPostNo = postNo;
      }
    } else {
      deselectPost();
      deselectLines();
      $currPost = null;
      selector = null;
      currPostNo = null;
    }
  }

  function getNumLines(postNo) {
    const el = document.getElementById(`post${postNo}`);
    if(el == null) return null;
    return $(el).find(lineSelector).length;
  }

  scrollPadding.addHashChangeHandler(function(hash) {
    if(hash == null) return false;
    const match = hash.match(LINE_PAT);
    if(!match) return false;
    const [, postStr, lineStr] = match;
    const postNo = Number(postStr);
    const numLines = getNumLines(postNo);
    const ranges = lineStr
      .split(',')
      .map(s => s.split('-').map(Number))
      .map(x => x.length === 1 ? [x[0], x[0]] : x)
      .filter(x => x[0] <= x[1])
      .map(x => [Math.max(1, x[0]), Math.min(numLines, x[1])]);
    setSelection({ postNo: postNo });
    selector.setRanges(ranges);
    return true;
  });

  $(containerSelector).on('click', lineSelector, function(event) {
    event.preventDefault();
    clicked = true;
    const $clickedLine = $(event.target).parent();
    const [postNo, lineNo] = getPostAndLine($clickedLine);
    setSelection({ postNo: postNo });
    // Treat the Alt, Meta, and Ctrl keys as the same. This is necessary on
    // Macs, where Ctrl + Click is intercepted by the browser and opens a
    // context menu instead. On Macs, the Option key counts as the Alt key,
    // and the Command key counts as the Meta key. Also note that, at least in
    // KDE, Alt + Click is intercepted and is used to move the window.
    const ctrl = event.ctrlKey || event.altKey || event.metaKey;
    selector.click(lineNo, { shift: event.shiftKey, ctrl });
  });

  scrollPadding.on('default', function(hash) {
    if(hash == null) {
      setSelection({});
    } else {
      const el = document.getElementById(hash);
      if(el == null) {
        setSelection({});
      } else {
        setSelection({ $post: $(el) });
      }
    }
  });
}

const POST_LINE_PAT = /^post(\d+)-line(\d+)$/;

function getPostAndLine($line) {
  const [, postStr, lineStr] = $line.prop('id').match(POST_LINE_PAT);
  return [Number(postStr), Number(lineStr)];
}

function getLineEl(postNo, lineNo) {
  return document.getElementById(`post${postNo}-line${lineNo}`);
}

function rangesToLines(ranges) {
  const result = [];
  for(let [lo, hi] of ranges) {
    for(let i = lo; i <= hi; ++i) {
      result.push(i);
    }
  }
  return result;
}

function rangesToLineEls(postNo, ranges) {
  return rangesToLines(ranges).map(lineNo => getLineEl(postNo, lineNo));
}

function rangesToHash(postNo, ranges) {
  if(ranges.length > 0) {
    const parts = ranges
      .map(([lo, hi]) => (lo === hi ? String(lo) : `${lo}-${hi}`));
    return `post${postNo}-line${parts.join(',')}`;
  } else {
    return null;
  }
}
