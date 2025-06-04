function bubbleSort(arr) {
  let n = arr.length;
  for(let i = 0; i < n-1; i++) {
    for(let j = 0; j < n-i-1; j++) {
      comparisons++;
      if(arr[j] > arr[j+1]) [arr[j], arr[j+1]] = [arr[j+1], arr[j]];
    }
  }
  return arr;
}

function insertionSort(arr) {
  for(let i = 1; i < arr.length; i++) {
    let key = arr[i], j = i - 1;
    while(j >= 0 && arr[j] > key) {
      comparisons++;
      arr[j + 1] = arr[j];
      j--;
    }
    comparisons++;
    arr[j + 1] = key;
  }
  return arr;
}

function quickSort(arr) {
  comparisons++;
  if (arr.length <= 1) return arr;
  let pivot = arr[arr.length - 1];
  let left = [], right = [];

  for(let i = 0; i < arr.length - 1; i++) {
    comparisons++;
    (arr[i] < pivot ? left : right).push(arr[i]);
  }

  return [...quickSort(left), pivot, ...quickSort(right)];
}

function selectionSort(arr) {
  let n = arr.length;
  for(let i = 0; i < n; i++) {
    let minIdx = i;
    for(let j = i+1; j < n; j++) {
      comparisons++;
      if(arr[j] < arr[minIdx]) minIdx = j;
    }
    [arr[i], arr[minIdx]] = [arr[minIdx], arr[i]];
  }
  return arr;
}

function mergeSort(arr) {
  comparisons++;
  if (arr.length <= 1) return arr;

  const mid = Math.floor(arr.length / 2);
  const left = mergeSort(arr.slice(0, mid));
  const right = mergeSort(arr.slice(mid));

  return merge(left, right);
}

function merge(left, right) {
  let result = [], i = 0, j = 0;
  while(i < left.length && j < right.length) {
    comparisons++;
    result.push(left[i] < right[j] ? left[i++] : right[j++]);
  }
  return result.concat(left.slice(i)).concat(right.slice(j));
}
