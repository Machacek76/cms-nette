module.exports = function (grunt) {
    var dir = {
        cms: './../www/cms/',
        frontend: './../www/frontend/'
    };



    grunt.initConfig({

        dir: dir,

        /**************************************************
         *  SASS
         **************************************************/
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },

                files: {
                    '<%=  dir.cms %>css/dist/main.css': '<%= dir.cms %>css/scss/main.scss',
                    '<%=  dir.frontend %>css/dist/main.css': '<%= dir.frontend %>css/scss/main.scss',
                }
            }

        },

        /**************************************************
         *  JS
         **************************************************/

        concat: {
            basic: {
                src: ['<%= dir.cms %>components/*.js'],
                dest: '<%= dir.cms %>dist/main.js',
            },
            extras: {
                src: ['<%= dir.frontend %>components/*.js'],
                dest: '<%= dir.frontend %>dist/mainfrontend.js',
            },
        },

        uglify: {
            options: {
                mangle: false,
                sourceMap: false,
//                  sourceMapName: '<%= dir.js_default %>/dist/cnc-video-player-fp-7.map'
            },
            js: {
                // Specifikace souborů pro minifikaci
                files: {
                    '<%= dir.cms %>js/dist/main.min.js': ['<%= dir.cms %>js/dist/main.js'],
                    '<%= dir.frontend %>js/dist/main.min.js': ['<%= dir.frontend %>js/dist/main.js']
                }
            }
        },

        /**************************************************
         *  WATCH
         **************************************************/
        watch: {
            sass: {
                files: [
                    '<%=  dir.frontend %>css/scss/**/*.{scss, sass}',
                    '<%= dir.cms %>css/scss/**/*.{scss,sass}'
                ],
                tasks: ['build:css'],
                options: {
                    //  spawn: false,
                },
            },
            /*
            js: {
                files: ['<%=  dir.frontend %>js/components/*.js', '<%= dir.frontend %>js/components/*.js'],
                tasks: ['build:js'],
                options: {
                    //  spawn: false,
                },
            },
            */
        },

        /**************************************************
         *  BROWSERSYNC
         **************************************************/

        browserSync: {
            dev: {
                bsFiles: {
                    src: [
                          '<%= dir.frontend %>/dist/**/*.css'
                        , '<%= dir.cms %>/css/**/*.css'
                        , './../app/**/*.php'
                        , './../app/**/*.latte'
                    ]
                },
                options: {
                    proxy: 'nette-cms.devel'
                }
            }
        },

    });


    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');



    grunt.registerTask('build:css', ['sass']);
    grunt.registerTask('watching', ['watch:sass']);
    grunt.registerTask('livereload', ['browserSync', 'watch:sass']);
    grunt.registerTask('ngLive', ['angular-builder']);

};