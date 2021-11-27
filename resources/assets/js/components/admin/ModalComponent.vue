<template>
  <div id="app">
    <button @click="showModal = true" class="button-modal">Show Modal</button>
    <transition name="fade" appear>
      <div class="modal-overlay-modal" v-if="showModal" @click="showModal = false"></div>
    </transition>
    <transition name="pop" appear>
      <div class="modal-modal" role="dialog" v-if="showModal">
        <h1>Vue Transitions</h1>
        <p>
          The
          <code>&lt;transition&gt;</code> component in Vue can create wonderful animated entrances and exits.
        </p>
        <button @click="showModal = false" class="button-modal">Hide Modal</button>
      </div>
    </transition>
  </div>
</template>

<<script>
export default {
name: "modal-component",
  data() {
    return {
		showModal: false
    };
  },
  methods:{

  }
}
</script>
