/* ==========================================
   HARITA MUSIC ACADEMY - CHART.JS WRAPPER
   ========================================== */

const ChartManager = {
  _charts: {},

  // Draw a smooth line chart using Chart.js
  drawLineChart: (canvasId, dataPoints, labels) => {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    // Destroy existing chart to prevent canvas reuse error
    if (ChartManager._charts[canvasId]) {
      ChartManager._charts[canvasId].destroy();
    }

    const ctx = canvas.getContext("2d");

    // Create gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, "rgba(20, 184, 166, 0.25)");
    gradient.addColorStop(1, "rgba(20, 184, 166, 0.0)");

    const config = {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          data: dataPoints,
          borderColor: '#0d9488', // Teal primary
          borderWidth: 3,
          fill: true,
          backgroundColor: gradient,
          tension: 0.4, // Bezier curve smoothness
          pointBackgroundColor: '#ffffff',
          pointBorderColor: '#064e3b', // Deep green border
          pointBorderWidth: 2.5,
          pointRadius: 6,
          pointHoverRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false // Hide default series legend
          },
          tooltip: {
            backgroundColor: '#064e3b',
            titleFont: { family: 'Poppins', weight: 'bold' },
            bodyFont: { family: 'Poppins' },
            padding: 10,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                let value = context.parsed.y;
                // Add rupee prefix for revenue/sales
                if (canvasId.toLowerCase().includes("revenue") || canvasId.toLowerCase().includes("sales")) {
                  return " ₹" + value.toLocaleString('en-IN');
                }
                return " " + value;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                family: 'Poppins',
                weight: '600',
                size: 10
              },
              color: '#047857' // Teal label color
            }
          },
          y: {
            grid: {
              color: '#f1f5f9',
              drawBorder: false
            },
            ticks: {
              font: {
                family: 'Poppins',
                weight: '600',
                size: 10
              },
              color: '#047857',
              callback: function(value) {
                if (canvasId.toLowerCase().includes("revenue") || canvasId.toLowerCase().includes("sales")) {
                  return "₹" + value;
                }
                return value;
              }
            }
          }
        }
      }
    };

    ChartManager._charts[canvasId] = new Chart(ctx, config);
  },

  // Draw a bar chart using Chart.js
  drawBarChart: (canvasId, values, labels) => {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    if (ChartManager._charts[canvasId]) {
      ChartManager._charts[canvasId].destroy();
    }

    const ctx = canvas.getContext("2d");

    // Alternating colors for bars
    const backgroundColors = values.map((_, i) => i % 2 === 0 ? '#064e3b' : '#10b981');

    const config = {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          data: values,
          backgroundColor: backgroundColors,
          borderRadius: 8, // Rounded top corners
          borderSkipped: 'bottom',
          barPercentage: 0.5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#064e3b',
            titleFont: { family: 'Poppins', weight: 'bold' },
            bodyFont: { family: 'Poppins' },
            padding: 10,
            cornerRadius: 8
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                family: 'Poppins',
                weight: '600',
                size: 10.5
              },
              color: '#047857'
            }
          },
          y: {
            grid: {
              color: '#f1f5f9',
              drawBorder: false
            },
            ticks: {
              font: {
                family: 'Poppins',
                weight: '600',
                size: 10
              },
              color: '#047857'
            }
          }
        }
      }
    };

    ChartManager._charts[canvasId] = new Chart(ctx, config);
  },

  // Keep circular progress rings custom since Chart.js is overkill for simple circles
  drawProgressRing: (containerId, percent, strokeColor = "#059669") => {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = "";
    container.style.position = "relative";
    container.style.display = "inline-flex";
    container.style.alignItems = "center";
    container.style.justifyContent = "center";

    const ringSize = 85;
    const strokeWidth = 8;
    const radius = (ringSize - strokeWidth) / 2;
    const circumference = radius * 2 * Math.PI;
    const strokeDashoffset = circumference - (percent / 100) * circumference;

    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", ringSize);
    svg.setAttribute("height", ringSize);
    svg.style.transform = "rotate(-90deg)";

    // Background track circle
    const track = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    track.setAttribute("stroke", "#f1f5f9");
    track.setAttribute("fill", "transparent");
    track.setAttribute("stroke-width", strokeWidth);
    track.setAttribute("r", radius);
    track.setAttribute("cx", ringSize / 2);
    track.setAttribute("cy", ringSize / 2);

    // Indicator circle
    const indicator = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    indicator.setAttribute("stroke", strokeColor);
    indicator.setAttribute("fill", "transparent");
    indicator.setAttribute("stroke-width", strokeWidth);
    indicator.setAttribute("r", radius);
    indicator.setAttribute("cx", ringSize / 2);
    indicator.setAttribute("cy", ringSize / 2);
    indicator.setAttribute("stroke-dasharray", circumference);
    indicator.setAttribute("stroke-dashoffset", strokeDashoffset);
    indicator.setAttribute("stroke-linecap", "round");

    svg.appendChild(track);
    svg.appendChild(indicator);

    // Percent text badge inside circle
    const text = document.createElement("div");
    text.style.position = "absolute";
    text.style.top = "50%";
    text.style.left = "50%";
    text.style.transform = "translate(-50%, -50%)";
    text.style.fontWeight = "bold";
    text.style.fontSize = "13px";
    text.style.color = "var(--text-main)";
    text.textContent = percent + "%";

    container.appendChild(svg);
    container.appendChild(text);
  }
};
