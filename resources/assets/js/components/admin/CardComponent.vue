<template>
  <div class="card">
    <p>{{ titulo }}</p>
  </div>
</template>

<script>
export default {
  name: "card-component",
  data() {
    return {
      titulo: "a"
    };
  }
};
</script>