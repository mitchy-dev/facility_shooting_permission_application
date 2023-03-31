const gulp = require('gulp');
const browserify = require('browserify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');
const babelify = require('babelify');

gulp.task('js', function () {
    return browserify({
        entries: './src/js/app.js',
        debug: true,
    })
        // .transform(babelify, {presets: ['@babel/preset-env']})
        .bundle()
        .pipe(source('bundle.js'))
        .pipe(buffer())
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./dist/js/'));
});

gulp.task('watch', function () {
    gulp.watch('./src/js/**/*.js', gulp.series('js'));
});

gulp.task('default', gulp.series('js', 'watch'));
