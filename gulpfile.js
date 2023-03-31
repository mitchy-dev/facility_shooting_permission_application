const gulp = require('gulp');
// const requireDir = require('require-dir');
//JavaScriptの圧縮に必要なパッケージ
const browserify = require('browserify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');

// const tasks = requireDir('./gulp-tasks', {recursive: true});
//CSSのビルドに必要なパッケージ
const cleanCSS = require('gulp-clean-css');
const imagemin = require('gulp-imagemin');
const changed = require('gulp-changed');

function minifyCSS() {
    return gulp.src('src/css/**/*.css')
        .pipe(cleanCSS())
        .pipe(gulp.dest('dist/css'));
}

function browserifyTask() { //タスクの関数を定義
    return browserify({entries: 'src/js/app.js', debug: true})
        // .transform('babelify', {presets: ['@babel/preset-env']})
        .bundle() //単一のファイルにまとめる
        .pipe(source('app.js')) //browserifyからgulpのストリームに変換
        .pipe(buffer()) //gulpストリームをJavaScriptオブジェクトに変換
        .pipe(sourcemaps.init({loadMaps: true})) //sourceマップを作成
        .pipe(rename({suffix: '.min'})) //ファイル名に.minを追加
        .pipe(uglify()) //JavaScriptを圧縮
        .pipe(sourcemaps.write('.')) //sourceマップを書き出し
        .pipe(gulp.dest('dist/js')); //ファイルに出力
}

//画像圧縮
function optimizeImages() {
    return gulp.src('src/images/**/*.{jpg,jpeg,png,gif,svg}')
        .pipe(changed('dist/images/**/*.{jpg,jpeg,png,gif,svg}')) //この記述がないとsrcとdistの同期が上手くいかない
        .pipe(imagemin([
            imagemin.gifsicle({interlaced: true}),
            imagemin.mozjpeg({quality: 75, progressive: true}),
            imagemin.optipng({optimizationLevel: 5}),
            imagemin.svgo({
                plugins: [
                    {removeViewBox: true},
                    {cleanupIDs: false}
                ]
            })
        ]))
        .pipe(gulp.dest('dist/images'));
}

function watch() {
    gulp.watch('src/js/**/*.js', browserifyTask); //監視対象のパスを指定、変更あれば指定のタスクを実行
    gulp.watch('src/css/**/*.css', minifyCSS);
    gulp.watch('src/images/**/*.{jpg,jpeg,png,gif,svg}', optimizeImages); // src/images/**/*.{jpg,jpeg,png,gif,svg}だと上手くいかない
}

exports.default = gulp.series(browserifyTask, minifyCSS, optimizeImages, watch); //デフォルトタスクを定義

