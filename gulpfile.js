const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));

/*
 |--------------------------------------------------------------------------
 | Asset Management
 |--------------------------------------------------------------------------
 |
 | Compile the application's Sass entry point into the public directory.
 |
 */

function styles() {
    return gulp.src('resources/assets/sass/app.scss')
        .pipe(sass.sync({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(gulp.dest('public/css'));
}

exports.default = styles;
exports.styles = styles;
