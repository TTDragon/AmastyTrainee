define([
    'uiComponent'
], function (Component) {
    return Component.extend({
        defaults: {
            hours: 0,
            minutes: 0,
            seconds: 0,
            stopWatch: 0,
            intervalDescriptor: null
        },

        initObservable: function () {
            this._super();
            this.observe(['hours', 'minutes', 'seconds', 'stopWatch']);
            this.stopWatch.subscribe(this.calculateTime.bind(this));

            return this;
        },

        calculateTime: function (secondsAmount) {
            this.hours(Math.trunc(secondsAmount / 3600));
            this.minutes(Math.trunc((secondsAmount - (this.hours() * 3600)) / 60));
            this.seconds(secondsAmount - this.hours() * 3600 - this.minutes() * 60);
        },

        init: function () {
            if (this.intervalDescriptor === null) {
                this.intervalDescriptor = setInterval(
                    this.increasingTime.bind(this),
                    1000
                );
            }
        },

        increasingTime: function () {
            this.stopWatch(this.stopWatch() + 1);
        },

        pause: function () {
            this.stopTime();
        },

        stopTime: function () {
            if (this.intervalDescriptor !== null) {
                clearInterval(this.intervalDescriptor);
                this.intervalDescriptor = null;
            }
        },

        stop: function () {
            this.stopTime();
            this.stopWatch(0);
        }
    });
});
