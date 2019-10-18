/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */

Nova.booting((Vue, router, store) => {
    Vue.component('index-email-on-event-email-field', require('./components/EmailField/IndexField'));
    Vue.component('form-email-on-event-email-field', require('./components/EmailField/FormField'));
    Vue.component('detail-email-on-event-email-field', require('./components/EmailField/DetailsField'));
});
