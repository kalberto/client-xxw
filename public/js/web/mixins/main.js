var main_mixin = {
  data: {
    main: {},
    base_url: document.location.origin + '/'
  },
  created: function created() {
    window.addEventListener('load', this.window_load);
    window.addEventListener('resize', this.window_resize);
    window.addEventListener('scroll', this.window_scroll);
  },
  destroyed: function destroyed() {
    window.removeEventListener('load', this.window_load);
    window.removeEventListener('resize', this.window_resize);
    window.removeEventListener('scroll', this.window_scroll);
  },
  watch: {},
  methods: {
    init: function init() {},
    initListeners: function initListeners() {
      $("aside#popup .pop_wrapper, aside#popup").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (e.target !== this) {
          return;
        } else {
          var popup = $(e.target);
          popup.closest('aside#popup').fadeOut();
        }
      });
    },
    hideLoader: function hideLoader() {
      if ($('aside#loader').length > 0) {
        setTimeout(function () {
          $("body").addClass("hide");
          setTimeout(function () {
            $("style#LoaderStyle").remove();
            $("aside#loader").remove();
            $("body").removeClass("hide");
          }, 1000);
        }, 1000);
      }
    },
    aside_fechar: function aside_fechar($event) {
      $($event.target).closest('aside#popup').fadeOut();
    },
    window_load: function window_load() {
      this.initListeners();
      this.set_localstorage();
    },
    window_resize: function window_resize() {},
    window_scroll: function window_scroll() {}
  }
};
