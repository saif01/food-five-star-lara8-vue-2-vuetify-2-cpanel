<script>
// Pie, Doughnut, Line, Bar

import { Bar, Line } from "vue-chartjs";

export default {
  props: ["chartData", "chartLabel"],

  extends: Bar,
  data() {
    return {
      datacollection: {
        labels: this.chartLabel,

        datasets: [
          {
            label: "Total Sale",
            backgroundColor: this.randColors(this.chartLabel.length),
            pointBackgroundColor: "white",
            borderWidth: 1,
            fill: false,
            data: this.chartData,
          },
        ],
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
          display: false,
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
    this.renderChart(this.datacollection, this.options);
  },
};
</script>