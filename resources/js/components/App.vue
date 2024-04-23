<template>
    <Suspense>
      <AppProvider>
        <Frame>
          <router-view></router-view>
        </Frame>
      </AppProvider>
    </Suspense>
  </template>
  
  <script setup>
  </script>
  