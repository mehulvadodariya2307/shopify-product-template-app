<template>
    <div class="loader">
      <div class="spinner"></div>
    </div>
  </template>
  <script>
  export default {
    name: 'Loader'
  };
  </script>
  <style scoped>
  .loader {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .spinner {
    width: 40px;
    height: 40px;
    margin: 100px auto;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: black;
    border-radius: 50%;
    animation: spin 0.8s infinite linear;
  }
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
  </style>