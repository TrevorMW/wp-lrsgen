'use strict';

// DEPENDENCIES
var gulp      = require('gulp'),
    sass      = require('gulp-sass'),
    concat    = require('gulp-concat'),
    rename    = require('gulp-rename'),
    uglify    = require('gulp-uglify');

var jsFiles     = './assets/js/**/*.js',
    jsDest      = './assets/static/js',
    scssFiles   = './assets/scss/',
    scssDest    = './assets/static/css',
    sassOptions = { outputStyle: 'compressed', includePaths: require('node-bourbon').includePaths };

// DETAILED TASKS
gulp.task( 'coreSass', function () {
  return gulp.src( scssFiles + 'core.scss' ).pipe( sass( sassOptions ).on( 'error', sass.logError ) ).pipe( gulp.dest( scssDest ) )
});

gulp.task( 'themeSass', function () {
  return gulp.src( scssFiles + 'style.scss' ).pipe( sass( sassOptions ).on( 'error', sass.logError ) ).pipe( gulp.dest( scssDest ) )
});

gulp.task( 'scripts', function() {
  return gulp.src(jsFiles)
    .pipe( concat( 'core.js') )
    //.pipe( uglify().on( 'error', function(e){ console.log(e); }) )
    .pipe( gulp.dest( jsDest ) );
});

// BUNDLED TASKS
gulp.task( 'sass', ['coreSass','themeSass'] );
gulp.task( 'compile', ['coreSass', 'themeSass', 'scripts'] );

// WATCH FOR CHANGES AND RUN BUNDLED TASKS
gulp.task( 'watch', function () {
  gulp.watch( './assets/scss/**/*.scss', ['sass'] );
  gulp.watch( './assets/js/core/**/*.js', ['scripts'] );
});

