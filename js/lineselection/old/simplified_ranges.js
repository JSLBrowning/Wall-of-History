export function findRange(ranges, value) {
  let lo = 0;
  let hi = ranges.length;
  while(lo < hi) {
    const mid = lo + Math.floor((hi - lo) / 2);
    const [rangeLo, rangeHi] = ranges[mid];
    if(value < rangeLo) {
      hi = mid;
    } else if(value <= rangeHi) {
      return [mid, true];
    } else {
      lo = mid + 1;
    }
  }
  return [lo, false];
}

export function addRange(ranges, [lo, hi]) {
  const [loIndex, loInside] = findRange(ranges, lo);
  let stopIndex;
  let newLo;
  if(loInside) {
    stopIndex = loIndex;
    newLo = ranges[loIndex][0];
  } else if(loIndex > 0 && lo === ranges[loIndex-1][1] + 1) {
    // If the new range is adjacent with the next lowest one, merge them.
    stopIndex = loIndex - 1;
    newLo = ranges[loIndex-1][0];
  } else {
    stopIndex = loIndex;
    newLo = lo;
  }
  const [hiIndex, hiInside] = findRange(ranges, hi);
  let startIndex;
  let newHi;
  if(hiInside) {
    startIndex = hiIndex + 1;
    newHi = ranges[hiIndex][1];
  } else if(hiIndex < ranges.length && hi === ranges[hiIndex][0] - 1) {
    // If the new range is adjacent with the next highest one, merge them.
    startIndex = hiIndex + 1;
    newHi = ranges[hiIndex][1];
  } else {
    startIndex = hiIndex;
    newHi = hi;
  }
  const result = ranges.slice(0, stopIndex);
  result.push([newLo, newHi]);
  result.push(...ranges.slice(startIndex));
  return result;
}

export function removeRange(ranges, [lo, hi]) {
  const fragments = [];
  const [loIndex, loInside] = findRange(ranges, lo);
  if(loInside) {
    const newLo = ranges[loIndex][0];
    // If the deleted range goes all the way to the beginning of the
    // overlapping range, delete it entirely.
    if(newLo !== lo) {
      fragments.push([newLo, lo-1]);
    }
  }
  const [hiIndex, hiInside] = findRange(ranges, hi);
  let startIndex;
  if(hiInside) {
    startIndex = hiIndex + 1;
    const newHi = ranges[hiIndex][1];
    // If the deleted range goes all the way to the end of the overlapping
    // range, delete it entirely.
    if(newHi !== hi) {
      fragments.push([hi+1, newHi]);
    }
  } else {
    startIndex = hiIndex;
  }

  const result = ranges.slice(0, loIndex);
  result.push(...fragments);
  result.push(...ranges.slice(startIndex));
  return result;
}

export function simplifyRanges(ranges) {
  let result = [];
  for(let range of ranges) {
    if(range[0] <= range[1]) {
      result = addRange(result, range);
    }
  }
  return result;
}
