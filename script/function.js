// Grzegorz Mrózek, Rafał Schmidt 2014-2015

var SCRIPT_URL = 'script/generate.php';
var MIN_TITLE_LENGTH = 4;
var MIN_ANSWER_LENGTH = 3;
var MULTIPLE_ANSWERS_VALUE = 'checkbox';
var IMAGES = [];

function criticalErrorPopup(error) {
	addNotification(error);
	showNotificationBox();
}

// Slider
$( document ).ready(function() {
	wentoxPerQuestionTimeSlider();
});

function wentoxPerQuestionTimeSlider() {
	$("#ui-slider").slider({
		range: "min",
		min: 5,
		value: 15,
		max: 90,
		animate: true,
		slide: function( event, ui ) { $("#wentoxPerQuestionTime").text(ui.value+"s"); }
	});
}

function getQuestionsContainer() {
	return $('article').eq(-1);
}
		
function countQuestions(htmlData) {
	if(arguments.length === 0) throw "Empty data";
	if(!isNaN(htmlData)) throw "Number as argument";
	return $(htmlData).children('section').length;
}

function getQuestionName(section) {
	return $(section).find('.questionName').val();
}

function iterateQuestionCounter(questionCounter) {
	if(arguments.length === 0) throw "No argument!";
	if(isNaN(questionCounter)) throw "Not a number!";
	return questionCounter += 1;
}

function createNewQuestion(newQuestionCounter) {
	if(arguments.length === 0) throw "No counter!";
	if(isNaN(newQuestionCounter)) throw "NaN!";
	
	return '<section class="question animated fadeInUp"> <div class="questionTitleContainer"> <input type="text" class="questionName" placeholder="np. Czy 2+3 to 5?"/> <span class="questionCounter">'+ newQuestionCounter +'</span> <button class="addImageBtn" onclick="addImageToAnswer(this);" type="button">+</button>  </div> <div class="questionType"> <p class="questionTypeParagraph">Rodzaj:</p> <div class="questionTypeContainer"> <div> <input type="radio" class="questionTypeInput" name="questionTypeNumber:'+ newQuestionCounter +'" value="single" checked="checked"> <label class="questionTypeLabel">jedna odpowiedź</label> </div> <div> <input type="radio" class="questionTypeInput" name="questionTypeNumber:'+ newQuestionCounter +'" value="checkbox"> <label class="questionTypeLabel">wiele odpowiedzi</label> </div> </div> </div> <p class="questionTip">Naciśnij na przycisk "P" aby zaznaczyć poprawną odpowiedź</p> <p class="questionAnswersParagraph">Odpowiedzi:</p> <div class="questionAnswersContainer"> <div> <input type="text" class="questionAnswerInput" placeholder="Podaj odpowiedź" /> <button type="button" class="trueAnswerButtonClicked" onClick="setTrueAnswer(this);">P</button> <button class="addImageBtn" onclick="addImageToAnswer(this);" type="button">+</button> </div> <div> <input type="text" class="questionAnswerInput" placeholder="Podaj odpowiedź" /> <button type="button" onClick="setTrueAnswer(this);">P</button> <button class="addImageBtn" onclick="addImageToAnswer(this);" type="button">+</button> </div> </div> <button type="button" class="addAnswer" onClick="addNewAnswer('+ newQuestionCounter +');">Dodaj odpowiedź</button> </section>';
}

function appendNewQuestionToQuiz(newQuestion, quiz) {
	if(arguments.length < 2) throw "Missing arguments";
	if(arguments.length > 2) throw "Too many arguments";
	$(quiz).append(newQuestion);
}

//MainFrame
function addNewQuestion() {
	var quiz = getQuestionsContainer();
	try {
		var questionCounter = countQuestions(quiz);
		var newQuestionCounter = iterateQuestionCounter(questionCounter);
		var newQuestion = createNewQuestion(newQuestionCounter);
		appendNewQuestionToQuiz(newQuestion, quiz);
	} catch(err) {
		criticalErrorPopup(err);
	}
}

function addNewImage(obj) {
	function previewFile(image, preview) {
		var file = image.files[0];
		var reader  = new FileReader();
			reader.onloadend = function () { preview.src = reader.result }
			file ? reader.readAsDataURL(file) : preview.src = "";
	}
	var addNewImageBox = "<div class='addImageBox'><img id='newImagePreview' /><input type='file' id='newImage' /><button id='newImageAdd'>Dodaj</button></div>";
	$('button.addQuestion.pageWidth').eq(0).after(addNewImageBox);

	$("#newImage").on("change", function() { previewFile(this, document.getElementById('newImagePreview')); });
	$("#newImageAdd").on("click touchdown", function() { 
	    IMAGES.push( { name: document.getElementById( 'newImage' ).files[0].name, file: document.getElementById( 'newImage' ).files[0] } );
		addNotification("Dodano zdjecie!");
		showNotificationBox();
		$('.addImageBox').remove();
		setTimeout(function() {
			closeNotificationBox();
		}, 1000);
	});
	
}

function setTrueAnswer(obj) {
	var question = $(obj).closest('section');
	var questionId = getQuestionId(question);
	var questionType = getQuestionType(questionId);
	if(questionType == 'single') setOneTrueAnswer(obj);
	else setMultipleAnswers(obj);
}

function clearAllAnswers(obj) {
	$(obj).closest('section').find('.questionAnswersContainer').find('button').removeClass('trueAnswerButtonClicked');
}

function setOneTrueAnswer(obj) {
	var questionContainer = $(obj).parent().parent();
	questionContainer.find('button').removeClass('trueAnswerButtonClicked');
	$(obj).addClass('trueAnswerButtonClicked');
}

function setMultipleAnswers(obj) {
	if($(obj).hasClass('trueAnswerButtonClicked')) $(obj).removeClass('trueAnswerButtonClicked');
	else $(obj).addClass('trueAnswerButtonClicked');
}

function getQuestionId(question) {
	return $(question).find('span').text();
}

function getQuestionType(questionId) {
	return $("input[name='questionTypeNumber:"+ questionId +"']:checked").val();
}

function createNewAnswer() {
	var questionHTML = '<div><input type="text" class="questionAnswerInput" placeholder="Podaj odpowiedź" ><button type="button" onClick="setTrueAnswer(this);">P</button><button class="addImageBtn" onclick="addImageToAnswer(this);" type="button">+</button></div>';
	return questionHTML;
}

function appendNewAnswerToQuestion(sectionNo, quiz) {
	if(arguments.length != 2) throw "Krytyczny błąd, brak argumentów";
	var newAnswer = createNewAnswer();
	$(quiz).children('section').eq(sectionNo - 1).children('.questionAnswersContainer').append(newAnswer);
}

function addNewAnswer(sectionNo) {
	var quiz = getQuestionsContainer();
	try {
		appendNewAnswerToQuestion(sectionNo, quiz);
	} catch (err) {
		criticalErrorPopup(err);
	}
}

function checkStringLength(string, requiredLength) {
    if (string.length >= requiredLength) return true;
    else return false;
}

function getPerQuestionTime() {
	return $("#ui-slider").slider('value');
}

function getQuizTitle() {
	return $('#wentoxNameInput').val();
}

function AreThereAnyQuestions() {
	try {
		if(countQuestions(getQuestionsContainer()) > 0) return true;
		else return false;
	} catch (error) {
		criticalErrorPopup(error);
		return false;
	}
}

function showNotificationBox() {
	$('#downloadGeneratedWentox').fadeIn('fast');
    hideFullBox('#downloadGeneratedWentox');
}

function addNotification(notificationText) {
	$('#downloadGeneratedWentox').append("<div class='notification'>" + notificationText + "</div>");
}

function addNotificationPlain(notificationText) {
	$('#downloadGeneratedWentox').append(notificationText);
}

function clearNotificationBox() {
	$('#downloadGeneratedWentox').children().remove();
}

function closeNotificationBox() {
	$('#downloadGeneratedWentox').fadeOut(function() {
		clearNotificationBox();
	});
}

function addImageToAnswer(obj) {
	if( IMAGES.length == 0) { addNotification("<strong>Brak dodanych zdjec</strong>") ; }
	else {
		addNotification("Dodane zdjęcia: ");
		IMAGES.forEach(function(element, index) {
			var reader = new FileReader();
				reader.readAsDataURL(element.file);
				reader.onloadend = function () { 
					addNotificationPlain("<button class='addImageToAnswer'><img alt='"+element.name+"' src='" + reader.result + "'/></button>"); 
				}
		}, this);
	}
	$(document).on('click touchdown', '.addImageToAnswer' , function(el){
		var input = $(obj).parent().children('input').val();
		if(el.target.alt) {
			input += " <img src='" + el.target.alt + "' />";
		} else {
			input += " <img src='" + el.target.firstChild.alt + "' />";
		}
			
		$(obj).parent().children('input').val(input);
		closeNotificationBox();
	});
	showNotificationBox();
}

$( document ).on('click' , 'input:radio' , function() { clearAllAnswers(this); });

function endWentox() {
	
	function addError(errorString) {
		errorTable.push(errorString);
	}
	
	function createQuestion(questionIndex, data) {
		function createQuestionAnswers(answerIndex, answerData) {
			var answer = {};
			answer.value = $(answerData).children('input').val();
            if(checkStringLength(answer.value, MIN_ANSWER_LENGTH)) {
            	answer.isTrue = $(answerData).children('button').hasClass('trueAnswerButtonClicked');
                question.answers.push(answer);
            } else addError("Za krótka odpowiedź nr " + (answerIndex + 1) + " w pytaniu nr " + counter);
		}
        var counter = questionIndex + 1;
		var question = {};
		
        question.title = getQuestionName(data);
		 							
        if(!checkStringLength(question.title, MIN_TITLE_LENGTH)) addError("Za krótkie pytanie nr " + counter);
			
        question.type = getQuestionType(counter);
		console.log(getQuestionType(counter));
        question.answers = [];
		
		$(data).children('.questionAnswersContainer').children('div').each(function (answerIndex, answerData) {
			createQuestionAnswers(answerIndex, answerData);
        });
        return question;
	}

	var form = new FormData();
	var errorTable = [];
    var perQuestionTime = getPerQuestionTime();
    var quizTitle = getQuizTitle();
	form.append('quizTitle', quizTitle);
	form.append('quizTime', perQuestionTime);
		
	if(!checkStringLength(quizTitle, MIN_TITLE_LENGTH)) addError("Za krótki tytuł quiza!");
	
    if(AreThereAnyQuestions()) {
        $(getQuestionsContainer()).find('section').each( function (questionIndex, questionData) {
			form.append('questions[]', JSON.stringify( createQuestion(questionIndex, questionData)) );
        });
    } else addError("Brak pytań!");
	
    if ( errorTable.length === 0 ) {

        IMAGES.forEach( function ( element ) {
            form.append( 'images[]', element.file, element.name );
        } );
		$.ajax({
			url: SCRIPT_URL,
			type: "POST",
			data: form,
			async: true,
			success: function (getReturn) {
				  clearNotificationBox();
				console.log(getReturn);
				addNotification(getReturn);
				showNotificationBox();
			},
			cache: false,
			contentType: false,
			processData: false
		});
    } else {
        clearNotificationBox();
        $(errorTable).each(function(counter, object) {
            addNotification((counter + 1) + '. ' + object);
        });
        showNotificationBox();
    }
}

function showProgressBar() {
	addNotificationPlain('<div class="sk-cube-grid"></div>');
	$('.sk-cube-grid').append('<div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div>');
	showNotificationBox();
}
$(document).ajaxStart(function(){
	showProgressBar();
});

function hideFullBox(item){
	$(document).mouseup(function (e){ //close (click outside)
		var container = $(item);
		if (!container.is(e.target)	&& container.has(e.target).length === 0) 
		{container.fadeOut();
		clearNotificationBox(); //To moje
		}
	});
}