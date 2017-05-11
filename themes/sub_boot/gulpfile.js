'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var copy = require('gulp-copy');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var jshist = require('gulp-jshint');

gulp.task('sass', function () {
  return gulp.src('./scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('dist/css'));
});

gulp.task('sass:watch', function () {
  gulp.watch('./scss/**/*.scss', ['sass']);
});

gulp.task('copycss', function() {
  return gulp
        .src('dist/css/*')
        .pipe(gulp.dest('css'));
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
