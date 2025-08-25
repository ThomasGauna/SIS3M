const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
let dibujando = false;

function redimensionar() {
	canvas.width = canvas.offsetWidth;
	canvas.height = 200;
}

window.addEventListener("resize", redimensionar);
redimensionar();

function getPos(evt) {
	let rect = canvas.getBoundingClientRect();
	let x = evt.clientX || evt.touches?.[0]?.clientX || 0;
	let y = evt.clientY || evt.touches?.[0]?.clientY || 0;
	return [x - rect.left, y - rect.top];
}

canvas.addEventListener("mousedown", e => {
	dibujando = true;
	let [x, y] = getPos(e);
	ctx.beginPath();
	ctx.moveTo(x, y);
});

canvas.addEventListener("mousemove", e => {
	if (!dibujando) return;
	let [x, y] = getPos(e);
	ctx.lineTo(x, y);
	ctx.stroke();
});

canvas.addEventListener("mouseup", () => dibujando = false);
canvas.addEventListener("mouseleave", () => dibujando = false);

canvas.addEventListener("touchstart", e => {
	e.preventDefault();
	dibujando = true;
	let [x, y] = getPos(e);
	ctx.beginPath();
	ctx.moveTo(x, y);
});

canvas.addEventListener("touchmove", e => {
	e.preventDefault();
	if (!dibujando) return;
	let [x, y] = getPos(e);
	ctx.lineTo(x, y);
	ctx.stroke();
});

canvas.addEventListener("touchend", () => dibujando = false);

function limpiar() {
	ctx.clearRect(0, 0, canvas.width, canvas.height);
}

document.getElementById("formulario").addEventListener("submit", function () {
	const dataURL = canvas.toDataURL("image/png");
	document.getElementById("firma").value = dataURL;
});