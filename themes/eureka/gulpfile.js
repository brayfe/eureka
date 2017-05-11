'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var copycss = require('gulp-copy');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var jshist = require('gulp-jshint');
var watch = require('gulp-watch');
const autoprefixer = require('gulp-autoprefixer');
require('gulp-run-seq');

gulp.task('sass', function () {
  return gulp.src('./scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('dist/css'));
});

// gulp.task('sass:watch', function () {
//   gulp.watch('./scss/**/*.scss', ['sass']);
// });
gulp.task('watch', function(end) {
  end.wait('key1', 'key2', 'key3');

  gulp.src('./scss/**/*.scss')
    .pipe(gulp.dest('dist/css'))
    .on('end', function(){
      end.notify('key1', function() {
        console.log('sass end.'); });
    });

  gulp.src('dist/css/*')
    .pipe(gulp.dest('css'))
    .on('end', end.notifier('key2',
      function() { console.log('copycss end.'); }
    ));

  gulp.src('css/*')
    .pipe(gulp.dest('css'))
    .on('end', end.notifier('key3',
      function() { console.log('autoprefixer end.'); }
    ));

});

// gulp.task('sass', function (end) {
//   gulp.src('./scss/**/*.scss')
//     .pipe(gulp.dest('dist/css'))
//     .on('end',end);

// });

gulp.task('autoprefixer', () =>
    gulp.src('css')
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        //.pipe(gulp.dest(''))
);

gulp.task('copycss', function() {
  return gulp
        .src('dist/css/*')
        .pipe(gulp.dest('css'));
});

gulp.task('watch', function(){
  gulp.watch('./scss/**/*.scss', ['sass','copycss']);
});

gulp.task('scripts', function() {
  return gulp.src('bower_components/bootstrap-sass/assets/javascripts/**/*js')
  .pipe(uglify())
  .pipe(gulp.dest('dist/js'));
});

gulp.task('copyjs', function() {
  return gulp
        .src('dist/js/**/*.js')
        .pipe(gulp.dest('js'));
});

gulp.task('default', ['sass', 'copycss', 'scripts', 'copyjs'], function() {});
