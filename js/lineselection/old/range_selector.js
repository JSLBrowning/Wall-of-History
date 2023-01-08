import EventEmitter from 'events';

import {
  findRange,
  addRange,
  removeRange,
  simplifyRanges
} from './simplified_ranges';

export default class RangeSelector extends EventEmitter {

  constructor() {
    super();
    // The line which currently serves as the "anchor" line when using
    // Shift + click. This is the last line selected with normal click or
    // Ctrl + click, or the first line in a selection that is made
    // programmatically with `setRanges()`.
    this.anchorLineNo = null;
    // The line most recently selected with Shift + click. It forms a range
    // relative to `anchorLineNo`, but if another line is selected with
    // Shift + click, the old value is discarded and the line clicked is used
    // instead.
    this.rangeLineNo = null;
    // All of the line ranges (simplified) that can no longer be changed (i.e.,
    // they were selected before the last usage of Ctrl + click).
    this.frozenRanges = [];
    // Whether the selection mode is positive or negative. When an already
    // selected line is clicked with Ctrl + click, the active range deselects
    // lines rather than selecting them.
    this.positiveMode = true;
    // `frozenRanges` and the range formed by `anchorLineNo` and `rangeLineNo`
    // combined into one set of simplified ranges.
    this.combinedRanges = [];
  }

  click(lineNo, { shift, ctrl }) {
    // NOTE: On Macs, Ctrl + click is intercepted by the OS and opens a
    // context menu instead. Because of this, you should use more than one
    // modifier key to act as Ctrl, such as the Alt (Option) key or Meta
    // (Command) key. On Macs, the Command key performs the equivalent
    // function in both Finder and Excel.
    if(shift) {
      // Shift + click.
      // Update the active range by setting `rangeLineNo` to the clicked
      // line. If there is no anchor, treat this as a normal click.
      // Note that Shift + click and Ctrl + Shift + click have the same
      // effect.
      if(this.anchorLineNo != null) {
        this.emit('toggle', this.combinedRanges, false);
        this.rangeLineNo = lineNo;
        this.combinedRanges = this.getCombinedRanges();
        this.emit('toggle', this.combinedRanges, true);
      } else {
        this.normalClick(lineNo);
      }
    } else {
      if(
        this.frozenRanges.length === 0 &&
        this.anchorLineNo === this.rangeLineNo &&
        this.anchorLineNo === lineNo
      ) {
        // Clicking the same line twice de-selects the line.
        this.deselect();
      } else {
        if(ctrl) {
          // Ctrl + click.
          // Freeze the current combined ranges. If an unselected line was
          // clicked, make the clicked line the active range. If a selected line
          // was clicked, deselect it, set it to the active range, and change
          // the selection mode to negative.
          this.emit('toggle', this.combinedRanges, false);
          this.frozenRanges = this.combinedRanges;
          this.anchorLineNo = lineNo;
          this.rangeLineNo = lineNo;
          this.positiveMode = !this.isSelected(lineNo);
          this.combinedRanges = this.getCombinedRanges();
          this.emit('toggle', this.combinedRanges, true);
        } else {
          // Select a single line.
          this.normalClick(lineNo);
        }
      }
    }
    this.emit('ranges', this.combinedRanges);
  }

  deselect() {
    this.emit('toggle', this.combinedRanges, false);
    this.anchorLineNo = null;
    this.rangeLineNo = null;
    this.frozenRanges = [];
    this.positiveMode = true;
    this.combinedRanges = [];
  }

  isSelected(lineNo) {
    const [, inside] = findRange(this.combinedRanges, lineNo);
    return inside;
  }

  setRanges(ranges) {
    this.emit('toggle', this.combinedRanges, false);
    this.combinedRanges = simplifyRanges(ranges);
    // When programmatically setting the selected ranges, always make the
    // first range the active range.
    this.frozenRanges = this.combinedRanges.slice(1);
    const firstSelection = this.combinedRanges.length > 0 ? this.combinedRanges[0] : null;
    this.anchorLineNo = firstSelection != null ? firstSelection[0] : null;
    this.rangeLineNo = firstSelection != null ? firstSelection[1] : null;
    this.positiveMode = true;
    this.emit('toggle', this.combinedRanges, true);
    this.emit('ranges', this.combinedRanges);
  }

  normalClick(lineNo) {
    this.emit('toggle', this.combinedRanges, false);
    this.anchorLineNo = lineNo;
    this.rangeLineNo = lineNo;
    this.frozenRanges = [];
    this.positiveMode = true;
    this.combinedRanges = [[lineNo, lineNo]];
    this.emit('toggle', this.combinedRanges, true);
  }

  getActiveRange() {
    let lo = this.anchorLineNo;
    let hi = this.rangeLineNo;
    if(hi < lo) {
      [lo, hi] = [hi, lo];
    }
    return [lo, hi];
  }

  getCombinedRanges() {
    const combine = this.positiveMode ? addRange : removeRange;
    return combine(this.frozenRanges, this.getActiveRange());
  }
}
