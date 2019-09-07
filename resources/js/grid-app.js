require('./bootstrap');


try {
    window.Popper = require('popper.js/dist/umd/popper').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}
