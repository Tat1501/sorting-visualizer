let array = [];
let swaps = 0;
let startTime;
let lastSortResult = null;


function initializeVisualizer() {
  const size = Math.floor(Math.random() * 3) + 20; // random 15,16, or 17
  array = Array.from({ length: size }, () => Math.floor(Math.random() * 200 + 10));
  drawBars(array);
}


async function bubbleSort(arr) {
  swaps = 0;
  const n = arr.length;
  for (let i = 0; i < n; i++) {
    for (let j = 0; j < n - i - 1; j++) {
      if (arr[j] > arr[j + 1]) {
        [arr[j], arr[j + 1]] = [arr[j + 1], arr[j]];
        swaps++;
        drawBars(arr);
        await new Promise(res => setTimeout(res, 20));
      }
    }
  }
}


function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function drawBars(array) {
    const container = document.getElementById("bars");
    container.innerHTML = "";
    const maxVal = Math.max(...array);

    array.forEach(value => {
        const bar = document.createElement("div");
        bar.classList.add("bar");
        bar.style.height = `${(value / maxVal) * 100}%`;
        bar.style.width = "30px";
        bar.style.margin = "0 5px";
        bar.style.backgroundColor = "#3498db";

        const label = document.createElement("div");
        label.classList.add("bar-label");
        label.textContent = value;
        bar.appendChild(label);

        container.appendChild(bar);
    });
}

async function quickSortWrapper(arr) {
    let swaps = 0;
    let start = performance.now();

    async function quickSort(arr, low, high) {
        if (low < high) {
            let pi = await partition(arr, low, high);
            await quickSort(arr, low, pi - 1);
            await quickSort(arr, pi + 1, high);
        }
    }

    async function partition(arr, low, high) {
        let pivot = arr[high];
        let i = low - 1;
        for (let j = low; j < high; j++) {
            if (arr[j] < pivot) {
                i++;
                [arr[i], arr[j]] = [arr[j], arr[i]];
                swaps++;
                drawBars(arr);
                await sleep(200);
            }
        }
        [arr[i + 1], arr[high]] = [arr[high], arr[i + 1]];
        swaps++;
        drawBars(arr);
        await sleep(200);
        return i + 1;
    }

    await quickSort(arr, 0, arr.length - 1);
    let end = performance.now();
    return { swaps, execution_time_ms: (end - start).toFixed(2) };
}


async function mergeSortWrapper(arr) {
    let swaps = 0;
    let start = performance.now();

    async function mergeSort(arr, l, r) {
        if (l >= r) return;

        const m = Math.floor((l + r) / 2);
        await mergeSort(arr, l, m);
        await mergeSort(arr, m + 1, r);
        await merge(arr, l, m, r);
    }

    async function merge(arr, l, m, r) {
        let L = arr.slice(l, m + 1);
        let R = arr.slice(m + 1, r + 1);
        let i = 0, j = 0, k = l;

        while (i < L.length && j < R.length) {
            arr[k++] = (L[i] <= R[j]) ? L[i++] : R[j++];
            swaps++;
            drawBars(arr);
            await sleep(200);
        }

        while (i < L.length) {
            arr[k++] = L[i++];
            swaps++;
            drawBars(arr);
            await sleep(200);
        }

        while (j < R.length) {
            arr[k++] = R[j++];
            swaps++;
            drawBars(arr);
            await sleep(200);
        }
    }

    await mergeSort(arr, 0, arr.length - 1);
    let end = performance.now();
    return { swaps, execution_time_ms: (end - start).toFixed(2) };
}


async function selectionSort(arr) {
    let swaps = 0;
    let start = performance.now();

    for (let i = 0; i < arr.length; i++) {
        let minIdx = i;
        for (let j = i + 1; j < arr.length; j++) {
            if (arr[j] < arr[minIdx]) {
                minIdx = j;
            }
        }
        if (minIdx !== i) {
            [arr[i], arr[minIdx]] = [arr[minIdx], arr[i]];
            swaps++;
            drawBars(arr);
            await sleep(200);
        }
    }

    let end = performance.now();
    return { swaps, execution_time_ms: (end - start).toFixed(2) };
}



async function insertionSort(arr) {
    let swaps = 0;
    let start = performance.now();

    for (let i = 1; i < arr.length; i++) {
        let key = arr[i];
        let j = i - 1;

        while (j >= 0 && arr[j] > key) {
            arr[j + 1] = arr[j];
            j--;
            swaps++;
            drawBars(arr);
            await sleep(200);
        }

        arr[j + 1] = key;
        drawBars(arr);
        await sleep(200);
    }

    let end = performance.now();
    return { swaps, execution_time_ms: (end - start).toFixed(2) };
}




async function runSort() {
  const algo = document.getElementById("algorithm").value;
  let arrCopy = [...array];
  swaps = 0; // reset
  startTime = performance.now();

  let result = null;

  if (algo === "bubble") {
    await bubbleSort(arrCopy);
    result = { swaps, execution_time_ms: (performance.now() - startTime).toFixed(2) };
  } else if (algo === "quick") {
    result = await quickSortWrapper(arrCopy);
  } else if (algo === "merge") {
    result = await mergeSortWrapper(arrCopy);
  } else if (algo === "selection") {
    result = await selectionSort(arrCopy);
  } else if (algo === "insertion") {
    result = await insertionSort(arrCopy);
  }

  const endTime = performance.now();
  const executionTime = (endTime - startTime).toFixed(2);

  lastSortResult = {
    algorithm: algo,
    array_size: arrCopy.length,
    swaps: result?.swaps ?? swaps,
    execution_time_ms: result?.execution_time_ms ?? executionTime
  };

  
  document.getElementById("saveBtn").disabled = false;
}



function saveSortDetails() {
  if (!lastSortResult) {
    alert("⚠️ No sort data to save! Please run a sort first.");
    return;
  }

  fetch("save_result.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(lastSortResult)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(" Sort details saved successfully in the database!");
      document.getElementById("saveBtn").disabled = true; 
    } else {
      alert(" Failed to save sort details: " + (data.message || "Unknown error"));
    }
  })
  .catch(err => {
    console.error("Error:", err);
    alert(" Error occurred while saving sort details. Check console for details.");
  });
}


