const reviewWrap = document.getElementById("reviewWrap");
const leftArrow = document.getElementById("leftArrow");
const rightArrow = document.getElementById("rightArrow");
const imgDiv = document.getElementById("imgDiv");
const personName = document.getElementById("personName");
const profession = document.getElementById("profession");
const description = document.getElementById("description");
const surpriseMeBtn = document.getElementById("surpriseMeBtn");
//const chicken = document.querySelector(".chicken");

let isChickenVisible;

let people = [
	{
		photo:
			'url("https://dainedvorak.com/assets/images/testimonials/josiphene-black-_-linkedin-photo.jpg")',
		name: "Josiphene Black",
		profession: "Group Director, SEO",
		description:
			"Daine is a unique and great asset to have on any team. His mind is the perfect combination of technical knowledge, strategic thinking, and quick execution which lead to him solving problems for his own clients and for the entire SEO team at Intouch. ....If you get the opportunity to work with Daine, you will see the impacts he has very quickly."
	},

	{
		photo:
			'url("https://dainedvorak.com/assets/images/testimonials/andee-lamonica-_-linkedin-photo.jpg")',
		name: "Andee LaMonica",
		profession: "Associate Creative Director/Copywriter",
		description:
			"As a writer, I cannot speak highly enough of the partnership Daine and I created while working together. Not only is he collaborative with team members and across departments, but he is dedicated to overall success for his brands and his passion for great work is infectious. It was truly a delight to work with him and one of the reasons I know how important it is for copy and SEO teams to work in parallel--I've seen the results and the potential for even more efficiencies. He made my work more enjoyable because I knew it would only be elevated with Daine's efforts. I wish I could clone him, and would love to work with him again."
	},

	{
		photo:
			'url("https://dainedvorak.com/assets/images/testimonials/kevin-stuart-cavanaugh-_-linkedin-photo.jpg")',
		name: "Kevin Stuart Cavanaugh",
		profession: "Owner at Law Firm of Kevin Stuart Cavanaugh",
		description:
			"Daine is a brilliant computer specialist. I think he can solve complicated problems that most people cannot....Daine was able to delete the hacker’s software so that my computer was restored and I was able to use it again."
	},
	{
		photo:
			'url("https://dainedvorak.com/assets/images/testimonials/erin-r-minne-linkedin-photo.jpg")',
		name: "Erin Minné",
		profession: "Lead Manager - Analytics & Insights | Woodruff",
		description:
			"Daine was on the same digital team as me at Woodruff, specializing in SEO. He was also an expert in all things AI automation and transformation. Daine was often a great resource when I had questions regarding ChatGPT extensions, ChatGPT best practices, or Excel formulas. He was always willing to support his greater team and solve complex problems."
	},
	{
		photo:
			'url("https://dainedvorak.com/assets/images/testimonials/jesse-brown-_-linkedin-photo.jpg")',
		name: "Jesse Brown",
		profession: "SEO Director",
		description:
			"As a leader, Daine was a teacher to those throughout our organization. He freely shared his strategic ideas and insights, giving him the ability to delegate complex efforts to internal team members and partnering SMEs. He was also able to consistently deliver top-quality results with ever-changing client expectations, needs, and deadlines."
	},

	{
		photo:
			'url("https://dainedvorak.com/assets/images/testimonials/andee-lamonica-_-linkedin-photo.jpg")',
		name: "Andee LaMonica",
		profession: "Associate Creative Director/Copywriter",
		description:
			"As a writer, I cannot speak highly enough of the partnership Daine and I created while working together. Not only is he collaborative with team members and across departments, but he is dedicated to overall success for his brands and his passion for great work is infectious. It was truly a delight to work with him and one of the reasons I know how important it is for copy and SEO teams to work in parallel--I've seen the results and the potential for even more efficiencies. He made my work more enjoyable because I knew it would only be elevated with Daine's efforts. I wish I could clone him, and would love to work with him again."
	}
];

imgDiv.style.backgroundImage = people[0].photo;
personName.innerText = people[0].name;
profession.innerText = people[0].profession;
description.innerText = people[0].description;
let currentPerson = 0;

//Select the side where you want to slide
function slide(whichSide, personNumber) {
	let reviewWrapWidth = reviewWrap.offsetWidth + "px";
	let descriptionHeight = description.offsetHeight + "px";
	//(+ or -)
	let side1symbol = whichSide === "left" ? "" : "-";
	let side2symbol = whichSide === "left" ? "-" : "";

	let tl = gsap.timeline();

	if (isChickenVisible) {
		tl.to(chicken, {
			duration: 0.4,
			opacity: 0
		});
	}

	tl.to(reviewWrap, {
		duration: 0.4,
		opacity: 0,
		translateX: `${side1symbol + reviewWrapWidth}`
	});

	tl.to(reviewWrap, {
		duration: 0,
		translateX: `${side2symbol + reviewWrapWidth}`
	});

	setTimeout(() => {
		imgDiv.style.backgroundImage = people[personNumber].photo;
	}, 400);
	setTimeout(() => {
		description.style.height = descriptionHeight;
	}, 400);
	setTimeout(() => {
		personName.innerText = people[personNumber].name;
	}, 400);
	setTimeout(() => {
		profession.innerText = people[personNumber].profession;
	}, 400);
	setTimeout(() => {
		description.innerText = people[personNumber].description;
	}, 400);

	tl.to(reviewWrap, {
		duration: 0.4,
		opacity: 1,
		translateX: 0
	});

	if (isChickenVisible) {
		tl.to(chicken, {
			duration: 0.4,
			opacity: 1
		});
	}
}

function setNextCardLeft() {
	if (currentPerson === 3) {
		currentPerson = 0;
		slide("left", currentPerson);
	} else {
		currentPerson++;
	}

	slide("left", currentPerson);
}

function setNextCardRight() {
	if (currentPerson === 0) {
		currentPerson = 3;
		slide("right", currentPerson);
	} else {
		currentPerson--;
	}

	slide("right", currentPerson);
}

leftArrow.addEventListener("click", setNextCardLeft);
rightArrow.addEventListener("click", setNextCardRight);

window.addEventListener("resize", () => {
	description.style.height = "100%";
});
