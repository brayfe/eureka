'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var clean = require('gulp-clean');
var copycss = require('gulp-copy');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var jshist = require('gulp-jshint');
var gulpSequence = require('gulp-sequence')
var watch = require('gulp-watch');
const autoprefixer = require('gulp-autoprefixer');
//require('gulp-run-seq');

// task functions
gulp.task('clean', function () {
    return gulp.src('dist/*')
        .pipe(clean({force: true}))
});

gulp.task('sass', function () {
  return gulp.src('scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('dist/css/'));
});

gulp.task('copycss', function() {
  return gulp.src('dist/css/*.css')
        .pipe(gulp.dest('css/'));
});

gulp.task('copyfonts', function() {
  return gulp
    .src('bower_components/bootstrap-sass/assets/fonts/bootstrap/*')
    .pipe(gulp.dest('fonts/icons'));
});

gulp.task('autoprefixer', () =>
    gulp.src('css')
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
);

gulp.task('watch', function() {
  gulp.watch(['scss/**/*.scss'], ['sass', 'copycss', 'autoprefixer']);
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


gulp.task('default', gulpSequence('clean', 'copyfonts', 'scripts', 'copyjs'));

// gulp.task('default', ['clean', 'copyfonts', 'scripts', 'copyjs'], function() {

// });
