let mix = require('laravel-mix');
mix.pug = require('laravel-mix-pug');
let fs = require('fs-extra');

mix.autoload({
	'jquery': ['$', 'window.jQuery', 'jQuery'],
});


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


//
// Web
//
// mix
// 	.options({
// 		processCssUrls: false
// 	})
// .pug((process.platform === "win32" ? 'resources\\pug' : 'resources/pug'), 'resources/views')
// Geral
//
// mix.pug('resources/pug/layouts/*', '../../views/layouts', {
// 		ext: '.blade.php',
// 	})
// 	.styles(['resources/assets/css/web/slick.css', 'resources/assets/css/web/slick-theme.css'], 'public/css/web/slick.css')
// 	.styles(['node_modules/normalize.css/normalize.css'], 'public/css/web/normalize.css')
// 	.scripts(['node_modules/jquery/dist/jquery.js', 'resources/assets/js/web/slick.min.js', ], 'public/js/web/vendor.js')
// 	.js('resources/assets/js/app.web.js', 'public/js/web/app.js')
// 	.stylus('resources/assets/stylus/web/main.styl', 'public/css/web', {
// 		compress: true
// 	})
// 	.copy('resources/assets/images/web', 'public/images/web')
// 	.copy('resources/assets/css/web/fonts', 'public/css/web/fonts');

//
// Admin
//
mix
	.copy('resources/assets/css/admin', 'public/css/admin')
	.copy('node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css', 'public/css/admin/datetimepicker.css')
	.copy('resources/assets/css/fonts', 'public/css/fonts')
	.copy('resources/assets/fonts', 'public/fonts')
	.copy('resources/assets/js/admin/modular_admin_app.js', 'public/js/admin/modular_admin_app.js')
	// .copy('resources/js/components/admin/components.admin.js', 'public/js/admin/components.js')
	.copy('resources/assets/js/admin/modular_admin_vendor.js', 'public/js/admin/modular_admin_vendor.js')
	.copy('node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js', 'public/js/admin/datetimepicker.js')
	// .copy('node_modules/vue-quill-editor/dist/vue-quill-editor.js', 'public/js/admin/vue-quill-editor.js')
	.copyDirectory('resources/assets/images/admin', 'public/images/admin')
	.sass('resources/assets/sass/admin/app.custom.scss', 'public/css/admin')
	.options({
		processCssUrls: false
	})
	.js('resources/assets/js/app.js', 'public/js/admin/app.js')
	.scripts(['resources/assets/js/controllers/api.js'], 'public/js/admin/api.js');

// from https://laracasts.com/discuss/channels/elixir/mix-all-js-in-directory
let getFiles = function (dir) {
	// get all 'files' in this directory
	// filter directories
	return fs.readdirSync(dir).filter(file => {
		return fs.statSync(`${dir}/${file}`).isFile();
	});
};
["admin/"].forEach(function (item) {
	getFiles('resources/assets/js/controllers/' + item).forEach(function (completeFileName) {
		if (completeFileName[0] != "_") {
			fileName = completeFileName.split('.');
			mix.babel(['resources/assets/js/controllers/' + item + completeFileName], 'public/js/' + item + fileName[0] + '.' + fileName[2])
		}
	});
	getFiles('resources/assets/js/mixins/' + item).forEach(function (completeFileName) {
		mix.babel(['resources/assets/js/mixins/' + item + completeFileName], 'public/js/' + item + '/mixins/' + completeFileName);
	});
});
