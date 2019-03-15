<template>
  <div>
    <modal title="File browser" :show="show" id="modal-body" v-ref:filemodal>
      <div id="file-modal" v-if="$refs.filemodal.show">
        <filebrowser :disk="disk" :path="path" :field="field" :index="index" show-disk-selector="false" show-path-navigator="false" v-ref:filesbrowser></filebrowser>
      </div>
    </modal>
  </div>
</template>

<script>
  import Modal from '../dialog/modal'
  import FileBrowser from '../files/selector'


  export default {

    /*----------------------------------------------
     * Components
     *----------------------------------------------*/
    components: {
      'modal': Modal,
      'filebrowser': FileBrowser
    },

    /*----------------------------------------------
     * Data
     *----------------------------------------------*/

    /*----------------------------------------------
     * Properties
     *----------------------------------------------*/
    props: {
      disk: {
        type: String,
        default: 'assessments'
      },
      path: {
        type: String,
        default: ''
      },
      field: {
        type: String,
        default: ''
      },
      index: {
        type: Number,
        default: false
      },
      show: {
        type: Boolean,
        default: false
      }
    },

    /*----------------------------------------------
     * Events
     *----------------------------------------------*/
    events: {
//      'file:selected': function (v) {
//        this.$refs.filemodal.cancel();
//      }
    },

    ready(){
    },

    /*----------------------------------------------
     * Methods
     *----------------------------------------------*/
    methods: {
      open() {
        this.$refs.filemodal.open();
      },
      close() {
        this.$refs.filemodal.cancel();
      }
    }
  }
</script>