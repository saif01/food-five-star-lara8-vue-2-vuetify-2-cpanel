<script>
// Pie, Doughnut, Line, Bar

import { Line } from "vue-chartjs";

export default {
  props: ["chartLavbel", "chartData"],

  extends: Line,
  data() {
    return {
      datacollection: {
        labels: this.chartLavbel,
        datasets: this.chartData,
      },
      options: {
        tooltips: {
          callbacks: {
            label: function (t, d) {
              var xLabel = d.datasets[t.datasetIndex].label;
              var yLabel =
                t.yLabel >= 1000
                  ? "৳ " +
                    t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                  : "৳ " + t.yLabel;
              return xLabel + ": " + yLabel;
            },
          },
        },
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
                callback: function (value, index, values) {
                  if (parseInt(value) >= 1000) {
                    return (
                      "৳ " +
                      value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    );
                  } else {
                    return "৳ " + value;
                  }
                },
              },
            },
          ],
          xAxes: [
            {
              gridLines: {
                display: false,
              },
            },
          ],
        },
        legend: {
          display: true,
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    };
  },

  methods: {
    // random color generator
    randColors(val = 2) {
      // empty Array
      const colors = [];
      for (let i = 1; i <= val; i++) {
        let color = "#" + (Math.random().toString(16) + "0000000").slice(2, 8);
        // colors push in array
        colors.push(color);
      }
      return colors;
    },
  },

  mounted() {
    // console.log("From Chart Com.:", this.chartLavbel);

    this.renderChart(this.datacollection, this.options);
  },
};
</script>