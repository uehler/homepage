module.exports = function (grunt) {
    grunt.initConfig({
        concat: {
            options: {
                separator: ';'
            },
            dist: {
                src: ['dev/_resources/js/*.js'],
                dest: 'dist/all.js'
            }
        },
        uglify: {
            options: {
                banner: '/*! test <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            dist: {
                files: {
                    'dist/all.min.js': ['<%= concat.dist.dest %>']
                }
            }
        },
        jshint: {
            files: ['Gruntfile.js', 'dev/_resources/js/*.js']
        },
        htmlmin: {
            dist: {
                options: {
                    removeComments: true,
                    collapseWhitespace: true
                },

                files: {
                    'dist/tpl/content.html': 'dev/tpl/content.html'
                }
            }
        },
        less: {
            dist: {
                options: {
                    compress: true,
                    sourceMap: true
                },
                files: {
                    "dist/all.css": "dev/_resources/less/all.less"
                }
            }
        },


        watch: {
            files: [
                'dev/_resources/js/*.js',
                'dev/_resources/less/*.less',
                'dev/tpl/*.html'
            ],
            tasks: ['jshint', 'concat', 'uglify', 'htmlmin', 'less', 'notify:watch']
        },

        notify: {
            watch: {
                options: {
                    message: 'compiled successful',
                    success: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-htmlmin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-notify');

    grunt.registerTask('default', ['jshint', 'concat', 'uglify', 'htmlmin', 'less', 'notify:watch']);
};
