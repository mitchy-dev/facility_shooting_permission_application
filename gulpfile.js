const gulp = require('gulp');
//JavaScriptの圧縮に必要なパッケージ
const browserify = require('browserify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');

//CSSのビルドに必要なパッケージ


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
        .pipe(gulp.dest('public/js')); //ファイルに出力
}

function watch() {
    gulp.watch('src/js/**/*.js', browserifyTask); //監視対象のパスを指定、変更あれば指定のタスクを実行
}

exports.default = gulp.series(browserifyTask, watch); //デフォルトタスクを定義
