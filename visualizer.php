<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sorting Visualizer</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Reset and base styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding-top: 40px;
    }

    /* Container */
    .container {
      width: 100rem;
      background: #fff;
      padding: 30px 40px 40px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      text-align: center;
      top: 90px;
      position: absolute;
    }

    /* Top Bar */
    .top-bar {
      width: 95rem;
      margin: auto 0 20px auto;
      background: black;
      color: white;
      padding: 10px 20px;
      border-radius: 0 0 10px 10px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 15px;
      box-sizing: border-box;
      position: absolute;
      top: -2px;
    }

    /* Welcome text */
    .top-bar span {
      font-weight: 600;
      font-size: 16px;
    }

    /* Forms inside top bar */
    .top-bar form {
      margin: 0;
      /* reset default */
    }

    /* Buttons in top bar */
    .top-bar button {
      background: white;
      color: #007bff;
      border: none;
      padding: 7px 15px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }

    .top-bar button:hover {
      background: #e6e6e6;
    }

    /* Controls section */
    .controls {
      margin-bottom: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
      flex-wrap: wrap;
    }

    .controls label {
      font-weight: 600;
      color: #555;
    }

    .controls select {
      padding: 8px 12px;
      font-size: 16px;
      border-radius: 6px;
      border: 1px solid #ccc;
      outline: none;
      min-width: 150px;
    }

    .controls button {
      padding: 9px 18px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      background-color: #3498db;
      color: white;
      transition: background-color 0.3s ease;
      height: 40px;
      min-width: 110px;
    }

    .controls button:hover:not(:disabled) {
      background-color: #2980b9;
    }

    .controls button:disabled {
      background-color: #a3c6e1;
      cursor: not-allowed;
    }

    /* Bars container */
    #bars {
      display: flex;
      justify-content: center;
      align-items: flex-end;
      height: 280px;
      gap: 6px;
      padding: 0 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: #fafafa;
    }

    /* Bars styling */
    .bar {
      background-color: #3498db;
      width: 30px;
      border-radius: 4px 4px 0 0;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: flex-end;
    }

    .bar-label {
      font-size: 12px;
      color: #222;
      margin-top: 5px;
      position: absolute;
      bottom: -18px;
      user-select: none;
      font-weight: 600;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 30px 25px;
      border-radius: 10px;
      width: 33rem;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      text-align: center;
      position: relative;
      left: 30rem;
      top: 10rem;
    }

    .loginForm{
      padding: 30px;
    }

    .close {
      position: absolute;
      right: 15px;
      top: 15px;
      font-size: 24px;
      cursor: pointer;
      color: #aaa;
      transition: color 0.3s ease;
    }

    .close:hover {
      color: #555;
    }

    .modal-content input[type="text"],
    .modal-content input[type="password"] {
      width: 100%;
      padding: 10px 12px;
      margin: 12px 0 18px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      outline: none;
      transition: border-color 0.3s ease;
    }

    .modal-content input[type="text"]:focus,
    .modal-content input[type="password"]:focus {
      border-color: #3498db;
    }

    .modal-content button {
      padding: 10px 18px;
      background-color: #3498db;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .modal-content button:hover {
      background-color: #2980b9;
    }
  </style>
</head>

<body onload="initializeVisualizer()">

  <div class="top-bar">
    <?php if ($isLoggedIn): ?>
      <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
      <form method="POST" action="logout.php">
        <button type="submit">Logout</button>
      </form>
    <?php else: ?>
      <button onclick="showLoginModal()">Login</button>
      <button onclick="showSignupModal()">Signup</button>
    <?php endif; ?>
  </div>

  <div class="container">
    <h1>Sorting Visualizer</h1>

    <div class="controls">
      <label for="algorithm">Choose Algorithm:</label>
      <select id="algorithm">
        <option value="bubble">Bubble Sort</option>
        <option value="quick">Quick Sort</option>
        <option value="merge">Merge Sort</option>
      </select>

      <button onclick="runSort()" id="sortBtn" <?= $isLoggedIn ? '' : 'disabled' ?>>Sort</button>
      <button onclick="initializeVisualizer()">Shuffle</button>
      <button id="saveBtn" onclick="saveSortDetails()" disabled>Save Sort</button>
    </div>

    <div id="bars"></div>
  </div>

  <!-- Login Modal -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('loginModal')">&times;</span>
      <h2>Login</h2>
      <form id="loginForm" class="loginForm">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>

  <!-- Signup Modal -->
  <div id="signupModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('signupModal')">&times;</span>
      <h2>Signup</h2>
      <form id="signupForm" class="loginForm">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Signup</button>
      </form>
    </div>
  </div>

  <script src="visualizer.js"></script>
  <script>
    function showLoginModal() {
      document.getElementById("loginModal").style.display = "block";
    }
    function showSignupModal() {
      document.getElementById("signupModal").style.display = "block";
    }
    function closeModal(modalId) {
      document.getElementById(modalId).style.display = "none";
    }

    document.getElementById("loginForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch("login.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("Login successful!");
            location.reload();
          } else {
            alert("Invalid credentials");
          }
        });
    });

    document.getElementById("signupForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch("signup.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("Signup successful! Please login.");
            closeModal("signupModal");
          } else {
            alert("Signup failed: " + data.message);
          }
        });
    });
  </script>
</body>

</html>