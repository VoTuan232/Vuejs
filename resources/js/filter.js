import Vue from 'vue';
import moment from 'moment';

Vue.filter('changeCreatedDate', function(created) {
  return moment(created).format('MMMM Do YYYY');
});

Vue.filter('upText', function(text) {
  return text.charAt(0).toUpperCase() + text.slice(1)
})