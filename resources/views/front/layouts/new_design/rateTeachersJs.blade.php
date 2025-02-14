<script src="{{asset('public/js/jquery-3.3.1.min.js')}}"></script>
<style>.fa-star{color: gold;padding: 5px; }</style>
<style>.partSection span{/*color: gold;*/padding: 5px; background-color: white; }</style>

<script>
    $(document).ready(function () {
        var __slice = [].slice;
        var clicks = 0;
        if((clicks==0)||(clicks==1)) {
            (function ($, window) {
                var Starrr;
                Starrr = (function () {
                    Starrr.prototype.defaults = {
                        rating: void 0,
                        numStars: 5,
                        change: function (e, value) {
                        }
                    };

                    function Starrr($el, options) {
                        var i, _, _ref,
                        _this = this;
                        this.options = $.extend({}, this.defaults, options);
                        this.$el = $el;
                        _ref = this.defaults;
                        for (i in _ref) {
                            _ = _ref[i];
                            if (this.$el.data(i) != null) {
                                this.options[i] = this.$el.data(i);
                            }
                        }
                        this.createStars();
                        this.syncRating();
                        this.$el.on('mouseover.starrr', 'span', function (e) {
                            return _this.syncRating(_this.$el.find('span').index(e.currentTarget) + 1);
                        });
                        this.$el.on('mouseout.starrr', function () {
                            return _this.syncRating();
                        });
                        this.$el.on('click.starrr', 'span', function (e) {
                            return _this.setRating(_this.$el.find('span').index(e.currentTarget) + 1);
                        });
                        this.$el.on('starrr:change', this.options.change);
                        }

                        Starrr.prototype.createStars = function () {
                            var _i, _ref, _results;
                            _results = [];
                            // for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                            //     _results.push(this.$el.append("<span class='far fa-star'></span>"));
                            // }
                            _results.push(this.$el.append("<span class='fas fa-star' style='font-size:12px;'></span>"));
                            _results.push(this.$el.append("<span class='far fa-star' style='font-size:14px;'></span>"));
                            _results.push(this.$el.append("<span class='far fa-star' style='font-size:16px;'></span>"));
                            _results.push(this.$el.append("<span class='far fa-star' style='font-size:18px;'></span>"));
                            _results.push(this.$el.append("<span class='far fa-star' style='font-size:20px;'></span>"));
                            //alert(_results);
                            return _results;
                        };

                        Starrr.prototype.setRating = function (rating) {
                            if (this.options.rating === rating) {
                                rating = void 0;
                            }
                            this.options.rating = rating;
                            this.syncRating();
                            return this.$el.trigger('starrr:change', rating);
                        };

                        Starrr.prototype.syncRating = function (rating) {
                            var i, _i, _j, _ref;

                            rating || (rating = this.options.rating);
                            if (rating) {
                                for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                                    this.$el.find('span').eq(i).removeClass(' far fa-star').addClass('fas fa-star');
                                }
                            }
                            if (rating && rating < 5) {
                                for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                                    this.$el.find('span').eq(i).removeClass('fas fa-star').addClass(' far fa-star');
                                }
                            }
                            if (!rating) {
                                return this.$el.find('span').removeClass('fas fa-star').addClass(' far fa-star');
                            }
                        };

                        return Starrr;

                    })();
                    return $.fn.extend({
                        starrr: function () {
                            var args, option;

                            option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
                            return this.each(function () {
                                var data;

                                data = $(this).data('star-rating');
                                if (!data) {
                                    $(this).data('star-rating', (data = new Starrr($(this), option)));
                                }
                                if (typeof option === 'string') {
                                    return data[option].apply(data, args);
                                }
                            });
                        }
                    });
                })(window.jQuery, window);

            }

            if((clicks==0)||(clicks==1)) {
                $(function () {
                    return $(".starrr").starrr();
                });
            }


            $('#stars').on('starrr:change', function (e, value) {
                clicks += 1;

                var postD_id = document.getElementById("postD_id").value;
                var postD_alisess = document.getElementById("postD_alisess").value;

                if((clicks==0)||(clicks==1)){
                $.ajax({
                    url:'/'+postD_id+'/rate',
                    datatype: 'html',
                    method: 'get',
                    data: {
                        pa: lang_ajax,
                        clicks: clicks,
                        postD_id: postD_id,
                        postD_alisess: postD_alisess,
                        value: value
                    },
                    success: function (data) {
                        displayRate(data);
                    },
                    error: function (request, error) {}

                });

            }
            });



            var rate = document.getElementById("rate").value;
            displayRate(rate);
        });

    function displayRate(data)
    {

        var wid = (data / 5) * 100;
        var margin;
        var color;
        var stuts;
        var star1 = "far fa-star";
        var star2 = "far fa-star";
        var star3 = "far fa-star";
        var star4 = "far fa-star";
        var star5 = "far fa-star";
        if (wid > 0 && wid <= 20) {
            star1 = "fa fa-star";
        }
        else if (wid > 20 && wid <= 40) {
            star1 = "fa fa-star";
            star2 = "fa fa-star";
        }
        else if (wid > 40 && wid <= 60) {
            star1 = "fa fa-star";
            star2 = "fa fa-star";
            star3 = "fa fa-star";
        }
        else if (wid > 60 && wid <= 80) {
            star1 = "fa fa-star";
            star2 = "fa fa-star";
            star3 = "fa fa-star";
            star4 = "fa fa-star";
        }

        else if (wid > 80 ) {
            star1 = "fa fa-star";
            star2 = "fa fa-star";
            star3 = "fa fa-star";
            star4 = "fa fa-star";
            star5 = "fa fa-star";
        }
        margin = "";
        color = "white";

        

        $('#s').html('<span class="' + star1 + ' "></span>' + '<span class="' + star2 + ' "></span>' + '<span class="' + star3 + ' "></span>' + '<span class="' + star4 + ' "></span>' + '<span class="' + star5 + ' "></span>');
        // console.log(star1 + '-' + star2 + '-' +star3 + '-' +star4 + '-' +star5 );
    }


</script>