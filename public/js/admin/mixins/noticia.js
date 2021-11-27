var noticia_mixin = {
  data: function data() {
    return {
      registro: {
        noticia_topo: '',
        noticia_interna: '',
        noticia_thumb: '',
        medias: [],
        mediasId: [],
        media: true
      },
      mediaDestaque: 0,
      editingMediaIndex: '',
      identifiers: {
        'noticia_topo': {
          'id': 'noticia_topo_id',
          'media': 'noticia_topo'
        },
        'noticia_interna': {
          'id': 'noticia_interna_id',
          'media': 'noticia_interna'
        },
        'noticia_thumb': {
          'id': 'noticia_thumb_id',
          'media': 'noticia_thumb'
        },
        'noticias': {
          'id': 'mediasId',
          'media': 'medias'
        }
      }
    };
  },
  methods: {
    initFather: function initFather() {
      this.sortable();
    },
    callLoadAssets: function callLoadAssets(pIdentifier) {
      this.media_root = pIdentifier.identifier;
      this.loadAssets();
    },
    selectMedia: function selectMedia(pId, pIndex) {
      if (this.media_root == 'noticias') {
        this.registro[this.identifiers[this.media_root].id].push(pId);
        this.registro[this.identifiers[this.media_root].media].push(this.medias[pIndex]);
      } else {
        this.registro[this.identifiers[this.media_root].id] = pId;
        this.registro[this.identifiers[this.media_root].media] = this.medias[pIndex];
        removeErrorMessage($("input[name='" + this.identifiers[this.media_root].id + "']"));
      }

      this.setSelectedMedia(pId);
    },
    removeMedia: function removeMedia(pIdentifier) {
      if (pIdentifier.identifier == "noticias") {
        this.registro[this.identifiers[pIdentifier.identifier].id].splice(pIdentifier.index, 1);
        this.registro[this.identifiers[pIdentifier.identifier].media].splice(pIdentifier.index, 1);
      } else {
        this.registro[this.identifiers[pIdentifier.identifier].id] = '';
        this.registro[this.identifiers[pIdentifier.identifier].media] = false;
      }
    },
    editMedia: function editMedia(pIdentifier) {
      $('#click_for_edit')[0].click();
      this.media_root = pIdentifier.identifier;
      this.editingMediaIndex = pIdentifier.index;
    },
    closeEditMedia: function closeEditMedia() {
      this.editingMediaIndex = '';
    },
    setDestaque: function setDestaque(pIdentifier) {
      if (pIdentifier.index !== this.mediaDestaque) {
        app.$data.registro[app.$data.identifiers[pIdentifier.identifier].media][pIdentifier.index].destaque = 1;
        app.$data.registro[app.$data.identifiers[pIdentifier.identifier].media][this.mediaDestaque].destaque = 0;
        this.mediaDestaque = pIdentifier.index;
      }
    },
    sortable: function sortable() {
      var sortable = new Sortable($('.images-container-sortable').get(0), {
        animation: 150,
        handle: ".control-btn.move",
        draggable: ".image-container",
        onMove: function onMove(evt) {
          var $relatedElem = $(evt.related);

          if ($relatedElem.hasClass('add-image')) {
            return false;
          }
        },
        onEnd: function onEnd(e) {
          app.change_pos(app.$data.registro.medias, e.oldIndex, e.newIndex).forEach(function (item, index) {
            app.$data.registro.medias.forEach(function (item2, index2) {
              if (app.$data.registro.medias[index2].id == item.id) app.$data.registro.medias[index2].order = index + 1;
            });
          });
        }
      });
    },
    change_pos: function change_pos(arr, old_index, new_index) {
      var new_array = arr.slice();

      while (old_index < 0) {
        old_index += new_array.length;
      }

      while (new_index < 0) {
        new_index += new_array.length;
      }

      if (new_index >= new_array.length) {
        var k = new_index - new_array.length;

        while (k-- + 1) {
          new_array.push(undefined);
        }
      }

      new_array.splice(new_index, 0, new_array.splice(old_index, 1)[0]);
      return new_array;
    }
  }
};
