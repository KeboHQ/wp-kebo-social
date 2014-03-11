module.exports = function(grunt) {

    // Project configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            options: {
                stripBanners: true,
                banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
                        ' * <%= pkg.homepage %>\n' +
                        ' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
                        ' * Licensed GPLv2+' +
                        ' */\n'
            },
            kebo_social: {
                src: [
                    'assets/js/src/kebo_social.js'
                ],
                dest: 'assets/js/kebo_social.js'
            }
        },
        jshint: {
            all: [
                'Gruntfile.js',
                'assets/js/src/**/*.js',
                'assets/js/test/**/*.js'
            ],
            options: {
                curly: true,
                eqeqeq: true,
                immed: true,
                latedef: true,
                newcap: true,
                noarg: true,
                sub: true,
                undef: true,
                boss: true,
                eqnull: true,
                globals: {
                    exports: true,
                    module: false
                }
            }
        },
        uglify: {
            all: {
                files: {
                    'assets/js/kebo_social.min.js': ['assets/js/kebo_social.js']
                },
                options: {
                    banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
                            ' * <%= pkg.homepage %>\n' +
                            ' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
                            ' * Licensed GPLv2+' +
                            ' */\n',
                    mangle: {
                        except: ['jQuery']
                    }
                }
            }
        },
        test: {
            files: ['assets/js/test/**/*.js']
        },
        sass: {
            all: {
                files: {
                    'assets/css/admin.css': 'assets/sass/admin.scss',
                    'assets/css/widgets.css': 'assets/sass/widgets.scss',
                    'inc/modules/social-sharing/assets/css/sharelinks.css': 'inc/modules/social-sharing/assets/sass/sharelinks.scss',
                }
            }
        },
        cssmin: {
            add_banner: {
                options: {
                    banner: '/* <%= pkg.title %> - v<%= pkg.version %>\n' +
                        ' * <%= pkg.homepage %>\n' +
                        ' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
                        ' * Licensed GPLv2+' +
                        '\n */\n'
                },
                files: {
                    'assets/css/widgets.min.css': ['assets/css/widgets.css'],
                    'assets/css/admin.min.css': ['assets/css/admin.css'],
                    'inc/modules/social-sharing/assets/css/sharelinks.min.css': ['inc/modules/social-sharing/assets/css/sharelinks.css']
                }
            }
        },
        watch: {
            sass: {
                files: [
                    'assets/sass/**/*.scss',
                    'inc/modules/**/assets/sass/**/*.scss'
                ],
                tasks: ['sass', 'cssmin'],
                options: {
                    debounceDelay: 500
                }
            },
            scripts: {
                files: [
                    'assets/js/src/**/*.js',
                    'inc/modules/**/assets/js/src/**/*.js'
                ],
                tasks: ['jshint', 'concat', 'uglify'],
                options: {
                    debounceDelay: 500
                }
            }
        },
        clean: {
            main: ['release/<%= pkg.version %>']
        },
        copy: {
            // Copy the plugin to a versioned release directory
            main: {
                src: [
                    '**',
                    '!node_modules/**',
                    '!release/**',
                    '!.git/**',
                    '!.sass-cache/**',
                    '!css/src/**',
                    '!js/src/**',
                    '!img/src/**',
                    '!images/src/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!.gitignore',
                    '!.gitmodules'
                ],
                dest: 'release/<%= pkg.version %>/'
            }
        },
        compress: {
            main: {
                options: {
                    mode: 'zip',
                    archive: './release/kebo_social.<%= pkg.version %>.zip'
                },
                expand: true,
                cwd: 'release/<%= pkg.version %>/',
                src: ['**/*'],
                dest: 'kebo_social/'
            }
        }
    });

    // Load other tasks
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compress');

    // Default task.

    grunt.registerTask('default', ['watch', 'jshint', 'concat', 'uglify', 'sass', 'cssmin']);

    grunt.registerTask('build', ['default', 'clean', 'copy', 'compress']);

    grunt.util.linefeed = '\n';
};