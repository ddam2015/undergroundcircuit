var gulp          = require('gulp');
// var browserSync   = require('browser-sync').create();
var $             = require('gulp-load-plugins')();
var autoprefixer  = require('autoprefixer');
var concat        = require('gulp-concat');
var stripDebug    = require('gulp-strip-debug');

var sassPaths = [
  'node_modules/foundation-sites/scss',
  'node_modules/motion-ui/src'
];

function sass() {
  return gulp.src('scss/style.scss')
    .pipe($.sourcemaps.init())
    .pipe($.sass({
      includePaths: sassPaths
    })
      .on('error', $.sass.logError))
    .pipe($.postcss([
      autoprefixer()
    ]))
    .pipe(gulp.dest('css'));
//     .pipe(browserSync.stream());
}
function sass_admin() {
  return gulp.src('scss/style-admin.scss')
    .pipe($.sass({
      includePaths: sassPaths
    })
      .on('error', $.sass.logError))
    .pipe($.postcss([
      autoprefixer()
    ]))
    .pipe(gulp.dest('css'));
//     .pipe(browserSync.stream());
}

// Combine Frontend JavaScript
function js_front() {
  return gulp.src(['js/inc/slick.min.js','js/inc/app.js','js/inc/woocomm.js'])
    .pipe($.concat('app.js'))
    .pipe(gulp.dest('js'));
}



function sass_prod() {
  return gulp.src('scss/style.scss')
    .pipe($.sourcemaps.init())
    .pipe($.sass({
      includePaths: sassPaths,
      outputStyle: 'compressed' // if css compressed **file size**
    })
      .on('error', $.sass.logError))
    .pipe($.postcss([
      autoprefixer({ overrideBrowserslist: ['last 2 versions', 'not dead'] })
    ]))
    .pipe(gulp.dest('css'));
//     .pipe(browserSync.stream());
}
function sass_admin_prod() {
  return gulp.src('scss/style-admin.scss')
    .pipe($.sass({
      includePaths: sassPaths,
      outputStyle: 'compressed' // if css compressed **file size**
    })
      .on('error', $.sass.logError))
    .pipe($.postcss([
      autoprefixer({ overrideBrowserslist: ['last 2 versions', 'not dead'] })
    ]))
    .pipe(gulp.dest('css'));
//     .pipe(browserSync.stream());
}

// Combine Frontend JavaScript
function js_front_prod() {
//   add full foundations js: 'node_modules/foundation-sites/dist/js/foundation.js'
//   return gulp.src(['../../../../../g365-dev/public/wp-content/plugins/g365-data-manager/js/g365_ajax_cookie_ls_app.js','js/inc/slick.min.js','js/inc/app.js','js/inc/woocomm.js'])
  return gulp.src(['js/inc/slick.min.js','js/inc/app.js','js/inc/woocomm.js'])
    .pipe($.concat('app.js'))
    .pipe($.uglify())
    .pipe(stripDebug())
    .pipe(gulp.dest('js'));
}


//start regular scss/js complie
function watcher_init() {
  gulp.watch(["scss/*.scss","../../../../../g365-dev/public/wp-content/themes/g365-press/scss/_customize.scss"], sass);
  gulp.watch("scss/*.scss", sass_admin);
  gulp.watch(['js/inc/**/*.js'], js_front);
}

gulp.task('prod_build', gulp.series(sass_admin_prod, sass_prod, js_front_prod));
gulp.task('default', gulp.series(sass_admin, sass, js_front, watcher_init));


//end regular compile

//start browsersync
// function serve() {
//   browserSync.init({
//     server: "./"
//   });

//   gulp.watch("scss/*.scss", sass);
//   gulp.watch("scss/*.scss", sass_admin);
//   gulp.watch(['../../plugins/g365-data-manager/js/g365_form_manager.js','../../plugins/g365-data-manager/js/livesearch.js','../../plugins/g365-data-manager/js/g365_cookie_handler.js','js/inc/**/*.js'], js_front);
//   gulp.watch("*.php").on('change', browserSync.reload);
// }

// gulp.task('sass', sass);
// gulp.task('sass_admin', sass_admin);
// gulp.task('js_front', js_front);
// gulp.task('serve', gulp.series('sass', serve));
// gulp.task('default', gulp.series('sass', serve));
//end browsersync
