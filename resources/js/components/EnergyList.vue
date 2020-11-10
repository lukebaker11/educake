<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        components: {
            loadingOverlay: Loading
        },
        props: [
            'data',
            'formRoute'
        ],
        data: function () {
            return {
                isLoading: false,
                fullPage: false,
                loader: 'bars',
                dateTo: null,
                dateFrom: null,
                intensityData: null,
                viewRegion: 0,
            }
        },
        methods: {
            percentage: function(value, total) {
                // we can return 0 if no total as a value will always lead to a total
                if (total === 0) {
                    return 0;
                }
                return (value/total) * 100;
            },
            formSubmit: function (e) {
                let self = this;
                this.isLoading = true;
                let form = document.getElementById(e.currentTarget.id).closest('form');
                axios.post(this.formRoute, {
                    data: this.serialize(form)
                }).then(function(response) {
                    self.isLoading = false;
                    console.log(response);
                    self.setData(response.data);
                }).catch(err => {
                    this.isLoading = false;
                });
            },
            setData: function(data) {
                this.dateTo = data.to;
                this.dateFrom = data.from;
                this.intensityData = data;
            },
            serialize: function (form) {
                var field, s = [];
                if (typeof form == 'object' && form.nodeName == "FORM") {
                    var len = form.elements.length;
                    for (let i=0; i<len; i++) {
                        field = form.elements[i];
                        if (field.name && !field.disabled && field.type != 'file' && field.type != 'reset' && field.type != 'submit' && field.type != 'button') {
                            if (field.type == 'select-multiple') {
                                for (let j=form.elements[i].options.length-1; j>=0; j--) {
                                    if(field.options[j].selected)
                                        s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[j].value);
                                }
                            } else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
                                s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value);
                            }
   this                     }
                    }
                }
                return s.join('&').replace(/%20/g, '+');
            }
        },
        created() {
            this.setData(this.data);
        }
    }
</script>

<template>
    <div class="section">
        <div class="container">
            <loading-overlay :active="isLoading" :is-full-page="fullPage" :loader="loader"></loading-overlay>
            <div class="columns">
                <div class="column">
                    <div class="container">
                        <form id="form" @submit.prevent="formSubmit">
                            <div class="container">
                                <div class="container">
                                    <label for="region">
                                        Region:
                                    </label>
                                    <div class="field">
                                        <div class="control">
                                            <div class="select is-primary">
                                                <select id="region" name="region" v-model="viewRegion">
                                                    <option v-for="(region, id) in intensityData.energyData" :value="id" >{{ region.region }}</option>
                                                    <option value="all">View All</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <label for="from">
                                        From:
                                    </label>
                                    <div class="field">
                                        <div class="control">
                                            <VueCtkDateTimePicker id="from" name="from" v-model="dateFrom" minute-interval="30" formatted='Do MMMM YYYY, h:mma'></VueCtkDateTimePicker>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <label for="to">
                                        To:
                                    </label>
                                    <div class="field">
                                        <div class="control">
                                            <VueCtkDateTimePicker id="to" name="to" v-model="dateTo" minute-interval="30" formatted='Do MMMM YYYY, h:mma'></VueCtkDateTimePicker>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <button id="go" type="submit" class="button is-primary">Go</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="column">
                    <div v-bind:class="[viewRegion !== id && viewRegion !== 'all' ? 'is-hidden' : '']" class="container is-padded" v-for="(region, id) in intensityData.energyData">
                        <h2 class="title is-3">{{ region.region }}</h2>
                        <h3 class="subtitle is-4" >Carbon intensity: {{ region.forecast }}</h3>
                        <table class="table">
                            <tr>
                                <th>Energy type</th><th colspan="2">Local generation</th><th>% of grid total</th>
                            </tr>
                            <tr v-for="(value, fuel) in region.generation">
                                <td>{{ fuel }}</td><td>{{ value.toFixed(1) }}</td><td>({{ percentage(value, region.forecast).toFixed(1) }}%)</td><td>{{ percentage(value, intensityData.energyData[0].forecast).toFixed(1) }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
