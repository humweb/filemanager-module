import Vue from "vue";
import Deferred from "../../lib/deferred";

import appState from '../../appState'
var store = {

  state: {
    fullpath: '',
    dir: '',
    disk: '',
    files: {},
  },

  fetch(disk, dir = '') {
    return Vue.http.get('/filemanager/api/disk/get', {disk: disk, dir: dir});
  },

  getFiles(disk, dir = '') {
    var d = new Deferred(),
    key = disk+dir,
    files = appState.get(key, 'files');

    if (files !== undefined) {
      d.resolve({data: files})
    }
    else {
      Vue.http.get('/filemanager/api/disk/get', {disk: disk, dir: dir}).then((res) => {
        appState.set(key, res.data, 'files');
        d.resolve(res)
      });
    }
    return d;
  },

  getDisks() {
    var d = new Deferred(),
    disks = appState.get('disks');
    if (disks) {
      d.resolve({data: {disks: appState.get('disks')}})
    }
    else {
      Vue.http.get('/filemanager/api/disks/list').then((res) => {
        appState.set('files.disks.list', res.data.disks);
        d.resolve(res)
      });
    }
    return d;
  }

}

export default store