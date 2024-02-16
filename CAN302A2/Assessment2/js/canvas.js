const dataset = {
    x: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    y: [150, 230, 224, 218, 135, 147, 260]
};
function draw() {
    var canvas = document.getElementById("drawing");
    if (canvas.getContext) {
        var ctx = canvas.getContext("2d");

        var width = canvas.width,
            height = canvas.height,
            padding = {top: 20, right: 40, bottom: 40, left: 40},
            bgTicks = 5,
            domain = seriesLabel(),
            range = [height - padding.bottom, 0];

        // 绘制背景网格线，横轴纵轴
        function drawOther() {
            // 绘制网格线
            for (let i = 0; i < bgTicks + 1; i++) {
                let y = Math.ceil(padding.top + ((height - padding.bottom - padding.top) / bgTicks * i)) + 0.5;
                new Text(domain[1] - domain[1] / bgTicks * i, padding.left - 6, y, "end", "middle").drawText(ctx);
                if (i < bgTicks) {
                    new Line(padding.left, y, width - padding.right, y, "#E0E6F1").drawLine(ctx);
                }
            }
            // 绘制横轴
            new Line(padding.left, height - padding.bottom + 0.5, width - padding.right, height - padding.bottom + 0.5, "#333").drawLine(ctx);
            // 绘制横轴刻度及刻度值
            for (let i = 0; i < dataset.x.length + 1; i++) {
                let x = Math.ceil(padding.left + (width - padding.left - padding.right) / dataset.x.length * i) + 0.5;
                let y = height - padding.bottom;
                let tx = Math.ceil(padding.left + (width - padding.left - padding.right) / dataset.x.length / 2 + (width - padding.left - padding.right) / dataset.x.length * i) + 0.5;
                // 绘制刻度
                new Line(x, y, x, y + 8, "#333").drawLine(ctx);
                if (i < dataset.x.length) {
                    new Text(dataset.x[i], tx, y + 6, "center", "top").drawText(ctx);
                }
            }
        }
        // 初始化绘制背景网格线，横轴纵轴
        drawOther();

        // 初始化绘制曲线和小圆圈
        var startX = padding.left + (width - padding.left - padding.right) / dataset.x.length / 2,
            startY = yScale(dataset.y[0]),
            endX = 0,
            endY = 0,
            count = 0;
        var timer = setInterval(function () {
            if (count <= dataset.y.length) {
                if (count > 0) {
                    endX = padding.left + (width - padding.left - padding.right) / dataset.x.length / 2 + (width - padding.left - padding.right) / dataset.x.length * count;
                    endY = yScale(dataset.y[count]);

                    new Line(startX, startY, endX, endY, "rgb(84, 112, 198)", 2).drawLine(ctx);
                    new Circle(startX, startY, 3, "rgb(84, 112, 198)").drawCircle(ctx);
                    startX = endX;
                    startY = endY;
                } else {
                    new Circle(startX, startY, 3, "rgb(84, 112, 198)").drawCircle(ctx);
                }
            } else {
                clearInterval(timer);
            }
            count++;
        }, 80);

        // 鼠标移入，重新绘制整个画布，曲线变宽，小圆圈变大
        canvas.onmousemove = function(event) {
            let e = event || window.event;
            // 清除画布
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            // 把除曲线之外的元素画上
            drawOther();
            // 计算当前鼠标位置
            let mx = e.clientX - canvas.offsetLeft;
            let my = e.clientY - canvas.offsetTop;
            // 一次性定义整条曲线
            drawAllLine();
            if (ctx.isPointInStroke(mx, my)) {
                ctx.lineWidth = 4;
                this.style.cursor = "pointer";  // 鼠标变手状
            } else {
                this.style.cursor = "default";
            }
            ctx.stroke();

            // 绘制小圆圈
            for (let i = 0; i < dataset.y.length; i++) {
                let x = padding.left + (width - padding.left - padding.right) / dataset.x.length / 2 + (width - padding.left - padding.right) / dataset.x.length * i;
                let y = yScale(dataset.y[i]);
                ctx.beginPath();
                ctx.fillStyle = "#fff";
                ctx.strokeStyle = "rgb(84, 112, 198)";
                ctx.lineWidth = 2;
                ctx.arc(x, y, 3, 0, Math.PI * 2);
                ctx.closePath();
                if (ctx.isPointInPath(mx, my)) {
                    ctx.beginPath();
                    ctx.fillStyle = "#fff";
                    ctx.strokeStyle = "rgb(84, 112, 198)";
                    ctx.lineWidth = 2;
                    ctx.arc(x, y, 5, 0, Math.PI * 2);
                    this.style.cursor = "pointer";
                }
                ctx.fill();
                ctx.stroke();
            }
        }

        // 创建y轴比例尺
        function yScale(value) {
            let scale = Math.abs(domain[1] - domain[0]) / Math.abs(range[0] - range[1]);
            return range[0] - value / scale;
        }
        // 计算数据中最大值与最小值
        function seriesLabel() {
            let arr = dataset.y.slice();    // 复制一个新数组出来
            let min = arr.sort()[0];
            let max = arr.sort()[arr.length-1];
            // 最小值大于0，则直接等于0，否则最小值的最大整数位-1，比如-123→-200
            min = min < 0 ? Math.floor(Math.floor(min) / Math.pow(10, (Math.floor(min).toString().length - 1))) * Math.pow(10, (Math.floor(min).toString().length - 1)) : 0;
            // 最大值等于0，则直接等于0，否则最大值的最大整数位+1，比如525→600
            max = max == 0 ? 0 : Math.ceil(Math.ceil(max) / Math.pow(10, (Math.ceil(max).toString().length - 1))) * Math.pow(10, (Math.ceil(max).toString().length - 1));
            return [min, max];
        }
        // 一次性定义整条曲线
        function drawAllLine() {
            let x0 = padding.left + (width - padding.left - padding.right) / dataset.x.length / 2;
            let y0 = yScale(dataset.y[0]);
            ctx.beginPath();
            ctx.lineWidth = 2;
            ctx.strokeStyle = "rgb(84, 112, 198)";
            ctx.moveTo(x0, y0);
            for (let i = 0; i < dataset.y.length; i++) {
                let x1 = padding.left + (width - padding.left - padding.right) / dataset.x.length / 2 + (width - padding.left - padding.right) / dataset.x.length * i;
                let y1 = yScale(dataset.y[i]);
                ctx.lineTo(x1, y1);
            }
        }
    }
}
// 定义线条对象
var Line = function(x1, y1, x2, y2, color, lineWidth) {
    this.x1 = x1;
    this.y1 = y1;
    this.x2 = x2;
    this.y2 = y2;
    this.color = color;
    this.lineWidth = lineWidth;
}
// 定义绘制线条方法
Line.prototype.drawLine = function (ctx) {
    ctx.beginPath();
    ctx.strokeStyle = this.color;
    ctx.lineWidth = this.lineWidth ? this.lineWidth : 1;
    ctx.moveTo(this.x1, this.y1);
    ctx.lineTo(this.x2, this.y2);
    ctx.closePath();
    ctx.stroke();
}
// 定义文本对象
var Text = function(value, x, y, textAlign, textBaseline) {
    this.value = value;
    this.x = x;
    this.y = y;
    this.textAlign = textAlign ? textAlign : "start";
    this.textBaseline = textBaseline ? textBaseline : "alphabetic";
}
// 定义绘制文本方法
Text.prototype.drawText = function(ctx) {
    ctx.font = "12px 'Microsoft YaHei'";
    ctx.textAlign = this.textAlign;
    ctx.textBaseline = this.textBaseline;
    ctx.fillStyle = "#333333";
    ctx.fillText(this.value, this.x, this.y);
}
// 定义圆对象
var Circle = function(x, y, r, color) {
    this.x = x;
    this.y = y;
    this.r = r;
    this.color = color;
}
// 定义绘制圆环方法
Circle.prototype.drawCircle = function(ctx) {
    ctx.beginPath();
    ctx.fillStyle = "#fff";
    ctx.strokeStyle = this.color;
    ctx.lineWidth = 2;
    ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
    ctx.closePath();
    ctx.fill();
    ctx.stroke();
}
