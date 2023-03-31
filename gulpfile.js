const gulp = require('gulp');
const browserify = require('browserify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');

function browserifyTask() {
    return browserify({entries: 'src/js/app.js', debug: true})
        // .transform('babelify', {presets: ['@babel/preset-env']})
        .bundle()
        .pipe(source('app.js'))
        .pipe(buffer())
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/js'));
}

function watch() {
    gulp.watch('src/js/**/*.js', browserifyTask);
}

exports.default = gulp.series(browserifyTask, watch);
