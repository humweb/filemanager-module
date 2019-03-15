<template>
  <div>
    <div class="row">
      <div v-if="showDiskSelector" class="col-sm-4">
        <label>Disks</label>
        <select v-model="disk" class="form-control">
          <option v-for="disk in disks" :value="$key">{{disk}}</option>
        </select>
      </div>
      <div v-if="showPathNavigator" class="col-sm-8"><label>Path</label>
        <input v-model="path" debounce="500" type="text" class="form-control">
      </div>
    </div>


    <ol class="breadcrumb">
      <li v-for="crumb in crumbs" :class="'active': !crumb.path">
        <a v-if="crumb.path || crumb.path == ''" href="#" @click.prevent="openFolder(crumb.path)">{{ crumb.label }}</a>
        <strong v-else>{{ crumb.label }}</strong>
      </li>
    </ol>

    <hr>

    <div v-for="row in files | chunk columnCount" class="row file-wrapper">
      <div v-for="item in row" class="col-sm-{{ columnSize }}">

        <div v-if="item.type == 'dir'" class="thumbnail text-center file-container" title="{{basename}}">
          <a href="#" @click.prevent="openFolder(item.path)" class="folder-item">
            <i class="fa fa-fw fa-folder fa-5x"></i></a>
          <div class="caption text-center">
            <div class="btn-group">
              <a href="#" @click.prevent="openFolder(item.path)" class="btn btn-secondary btn-outline btn-xs">
                {{ item.basename | truncate }}
              </a>
              <button type="button" class="btn btn-secondary btn-outline dropdown-toggle btn-xs" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <br>
              <ul class="dropdown-menu">
                <li><a href="#" class="btn-rename">Rename</a></li>
              </ul>
            </div>
          </div>
        </div>

        <div v-if="item.type == 'file'" class="thumbnail text-center file-container" title="{{basename}}">
          <a href="#" @click.prevent="selectFile(item)">
            <img v-if="isImage(item)" :src="fileUrl(item)" class="img-responsive">
            <i v-else class="fa fa-fw fa-5x {{ isAudio(item) ? 'fa-file-audio-o' : 'fa-file' }}"></i>
          </a>
          <div class="caption text-center">
            <div class="btn-group">
              <a href="#" @click.prevent="selectFile(item)" class="btn btn-secondary btn-outline btn-xs">
                {{ item.basename | truncate }}
              </a>

              <button type="button" class="btn btn-secondary btn-outline dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <br>
              <ul class="dropdown-menu">
                <li><a href="#" @click.prevent="selectFile(item)" class="btn-select">Select</a></li>
                <li><a href="#" class="btn-open">Open</a></li>
                <li><a href="#" class="btn-rename">Rename</a></li>
                <li><a href="#" class="btn-url">Url</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <paginate v-ref:paginate
              for="files"
              :records="allFiles.length"
              :per-page="limit"
              :chunk="pagination.chunk"
              :count-text="pagination.countText">
    </paginate>
  </div>
</template>

<script>

  import fileStore from './store'
  import VuePagination from "../pagination/paginate";
  import appState from '../../appState'
  import _slice from '../../util/slice'

  export default {
    props: {
      field: {
        type: String,
        default: ''
      },
      disk: {
        type: String,
        default: ''
      },
      disks: {
        type: Array,
        default: []
      },
      path: {
        type: String,
        default: ''
      },
//      files: {
//        type: Array,
//        default: []
//      },
      allFiles: {
        type: Array,
        default: []
      },
      index: {
        type: Number,
        default() {
          return false
        }
      },
      url: {
        type: String,
        default() {
          return ''
        }
      },
      columnCount: {
        type: Number,
        default() {
          return 4
        }
      },
      imageTypes: {
        type: Array,
        default() {
          return ['svg', 'jpg', 'png', 'gif'];
        }
      },
      audioTypes: {
        type: Array,
        default() {
          return ['mp3', 'ogg', 'wav'];
        }
      },
      showDiskSelector: {
        type: Boolean,
        default() {
          return true
        }
      },
      showPathNavigator: {
        type: Boolean,
        default() {
          return true
        }
      }
    },
    data() {
      return {
        url: 'http://192.168.100.204/gummo/_assets',
        busy: false,
        count: 0,
        limit: 25,
        page: 1,
        pagination: {
          chunk: 10,
          countText: '{count} records'
        }
      }
    },
    components: {
      'paginate': VuePagination
    },

    /*----------------------------------------------
     * Computed properties
     *----------------------------------------------*/
    computed: {
      files(){
        var o = this.$refs.paginate.getOffset;
        return _slice(this.allFiles || [], o.start, o.stop);
      },
      columnSize(){
        return Math.round(12 / parseInt(this.columnCount))
      },
      crumbs() {
        var str = [{label: 'Root', path: ''}];
        if (this.path !== '') {
          var s = this.path.split('/');
          for (var i = 0; i < (s.length - 1); i++) {
            str.push({label: s[i], path: this.path.substring(0, this.path.indexOf('/' + s[i]) + s[i].length + 1)});
          }
          str.push({label: s[i], path: null});
        }
        return str;
      }
    },

    /*----------------------------------------------
     * Lifecycle Hooks
     *----------------------------------------------*/
    beforeDestroy() {
      appState.set('file-selector-state', {
        disk: this.disk,
        path: this.path
      })
    },

    /**
     * Ready
     */
    ready() {
      this.$on('vue-pagination::files', function (page) {
        console.log('PAGE::', page)
        this.page = page;
      });
    },
    created() {
      let c = appState.get('file-selector-state');
      if (c && this.disk == '') {
        this.disk = c.disk;
        this.path = c.path;
      }
      fileStore.getFiles(this.disk, this.path).then((res) => {
        this.url = res.data.url;
        //this.files = [];
        if (this.showDiskSelector) {
          fileStore.getDisks().then((res2)=> {
            this.allFiles = res.data.directories;
            this.disks = res2.data.disks;
          });
        } else {
          this.allFiles = res.data.directories
        }
        this.count = this.allFiles.length;
      });
    },

    /*----------------------------------------------
     * Watch Data
     *----------------------------------------------*/
    watch: {
      'path': function (path) {
        this.reloadFiles();
      },
      'disk': function (disk) {
        this.path = '';
        this.reloadFiles();
      },
      'vue-pagination::files': function (page) {
        this.page = page;
      },
    },

    /*----------------------------------------------
     * Methods
     *----------------------------------------------*/
    methods: {

      openFolder(path) {
        this.path = path
      },

      isImage(file) {
        return this.imageTypes.indexOf(file.extension) !== -1;
      },
      isAudio(file) {
        return this.audioTypes.indexOf(file.extension) !== -1;
      },

      fileUrl(file) {
        return this.url + '/' + file.path;
      },

      selectFile(item) {
        this.$dispatch('file:selected', item, this.field, this.index);
      },

      reloadFiles() {
        if (this.disk !== '') {
          fileStore.getFiles(this.disk, this.path).then((res)=> {
            this.allFiles = res.data.directories;
            this.url = res.data.url;
            this.count = this.allFiles.length;
          });
        }
      },
      setFromUrlPath(url) {
        let parts = url.split('/');
        this.disk = parts.shift();
        this.path = parts.join('/');
      }
    }
  }
</script>

<style>
  .file-container {
    display: inline-block;
    width: 150px;
    padding-top: 12px;
    min-height: 159px;
  }
</style>
