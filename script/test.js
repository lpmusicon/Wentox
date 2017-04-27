QUnit.test( "Sprawdz ilosc pytan", function( assert ) {
	QUnit.expect( 1 );
	var fixture = $( "#qunit-fixture" );
	fixture.append("<section></section>");
	fixture.append("<section></section>");
	var questionsCount = countQuestions(fixture);
	assert.deepEqual(questionsCount, 2);
});

QUnit.test( "Dodaj jeden do licznika pytania", function( assert ) {
	QUnit.expect( 1 );
	var counter = iterateQuestionCounter(1);
	assert.deepEqual(counter, 2);
});

QUnit.test( "Stworz nowe pytanie", function( assert ) {
	QUnit.expect( 1 );
	var newQuestion = createNewQuestion(1);
	var fixture = $( "#qunit-fixture" );
	fixture.append(newQuestion);
	assert.deepEqual(countQuestions(fixture), 1);
});

QUnit.test( "Dodaj pytanie do quizu", function( assert ) {
	QUnit.expect( 1 );
	var question = createNewQuestion(1);
	var fixture = $( "#qunit-fixture" );
	appendNewQuestionToQuiz(question, fixture);
	assert.deepEqual(countQuestions(fixture), 1);
});

QUnit.test( "Tworzenie nowego pytania", function( assert ) {
	QUnit.expect( 1 );
	var fixture = $( "#qunit-fixture" );
	var newAnswer = createNewAnswer();
	fixture.append(newAnswer);
	assert.deepEqual(fixture.length, 1);
});

QUnit.test( "Dodaj odpowiedź do pytania", function( assert ) {
	QUnit.expect( 1 );
	var fixture = $( "#qunit-fixture" );
	fixture.append('<section></section><section><div class="questionAnswersContainer"></div></section><section></section>');
	appendNewAnswerToQuestion(2, fixture);
	assert.deepEqual(fixture.children('section').eq(1).length, 1, "OK @ InnerHTML: " + fixture.html() );
});

QUnit.test( "Podaj ID pytania na podstawie pytania", function( assert ) {
	QUnit.expect( 1 );
	var fixture = $( "#qunit-fixture" );
	fixture.append('<section class="question animated fadeInUp"> <div class="questionTitleContainer"> <input class="questionName" type="text" placeholder="np. Czy 2+3 to 5?"> <span class="questionCounter">1</span> </div> <div class="questionType"> <p class="questionTypeParagraph">Rodzaj:</p> <div class="questionTypeContainer"> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" checked="checked" value="single"> <label class="questionTypeLabel">jedna odpowiedź</label> </div> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" value="checkbox"> <label class="questionTypeLabel">wiele odpowiedzi</label> </div> </div> </div> <p class="questionTip">Naciśnij na przycisk "P" aby zaznaczyć poprawną odpowiedź</p> <p class="questionAnswersParagraph">Odpowiedzi:</p> <div class="questionAnswersContainer"> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button class="trueAnswerButtonClicked" onclick="setTrueAnswer(this);" type="button">P</button> </div> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button onclick="setTrueAnswer(this);" type="button">P</button> </div> </div> <button class="addAnswer" onclick="addNewAnswer(1);" type="button">Dodaj odpowiedź</button> </section>');
	var idRetrivedFromFunction = getQuestionId(fixture.html());
	assert.equal(idRetrivedFromFunction, 1);
});

QUnit.test( "Podaj typ pytania na podstawie pytania", function( assert ) {
	QUnit.expect( 1 );
	var fixture = $( "#qunit-fixture" );
	fixture.append('<section class="question animated fadeInUp"> <div class="questionTitleContainer"> <input class="questionName" type="text" placeholder="np. Czy 2+3 to 5?"> <span class="questionCounter">1</span> </div> <div class="questionType"> <p class="questionTypeParagraph">Rodzaj:</p> <div class="questionTypeContainer"> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" checked="checked" value="single"> <label class="questionTypeLabel">jedna odpowiedź</label> </div> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" value="checkbox"> <label class="questionTypeLabel">wiele odpowiedzi</label> </div> </div> </div> <p class="questionTip">Naciśnij na przycisk "P" aby zaznaczyć poprawną odpowiedź</p> <p class="questionAnswersParagraph">Odpowiedzi:</p> <div class="questionAnswersContainer"> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button class="trueAnswerButtonClicked" onclick="setTrueAnswer(this);" type="button">P</button> </div> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button onclick="setTrueAnswer(this);" type="button">P</button> </div> </div> <button class="addAnswer" onclick="addNewAnswer(1);" type="button">Dodaj odpowiedź</button> </section>');
	var retrivedType = getQuestionType(1);
	assert.equal(retrivedType, 'single');
});

QUnit.test( "Działanie kliknięcia przycisku, zmiana stanu reszty dla z danego pytania (radio)", function( assert ) {
	QUnit.expect( 2 );
	var fixture = $( "#qunit-fixture" );
	fixture.append('<section class="question animated fadeInUp"> <div class="questionTitleContainer"> <input class="questionName" type="text" placeholder="np. Czy 2+3 to 5?"> <span class="questionCounter">1</span> </div> <div class="questionType"> <p class="questionTypeParagraph">Rodzaj:</p> <div class="questionTypeContainer"> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" checked="checked" value="single"> <label class="questionTypeLabel">jedna odpowiedź</label> </div> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" value="checkbox"> <label class="questionTypeLabel">wiele odpowiedzi</label> </div> </div> </div> <p class="questionTip">Naciśnij na przycisk "P" aby zaznaczyć poprawną odpowiedź</p> <p class="questionAnswersParagraph">Odpowiedzi:</p> <div class="questionAnswersContainer"> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button id="theOtherOne" class="trueAnswerButtonClicked" onclick="setTrueAnswer(this);" type="button">P</button> </div> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button onclick="setTrueAnswer(this);" type="button" id="clickable">P</button> </div> </div> <button class="addAnswer" onclick="addNewAnswer(1);" type="button">Dodaj odpowiedź</button> </section>');
	$('#clickable').trigger("click");
	assert.equal($('#clickable').hasClass('trueAnswerButtonClicked'), true);
	assert.equal($('#theOtherOne').hasClass('trueAnswerButtonClicked'), false);
});

QUnit.test( "Działanie kliknięcia przycisku (checkbox)", function( assert ) {
	QUnit.expect( 2 );
	var fixture = $( "#qunit-fixture" );
	fixture.append('<section class="question animated fadeInUp"> <div class="questionTitleContainer"> <input class="questionName" type="text" placeholder="np. Czy 2+3 to 5?"> <span class="questionCounter">1</span> </div> <div class="questionType"> <p class="questionTypeParagraph">Rodzaj:</p> <div class="questionTypeContainer"> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" value="single"> <label class="questionTypeLabel">jedna odpowiedź</label> </div> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" checked="checked" value="checkbox"> <label class="questionTypeLabel">wiele odpowiedzi</label> </div> </div> </div> <p class="questionTip">Naciśnij na przycisk "P" aby zaznaczyć poprawną odpowiedź</p> <p class="questionAnswersParagraph">Odpowiedzi:</p> <div class="questionAnswersContainer"> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button id="theOtherOne" class="trueAnswerButtonClicked" onclick="setTrueAnswer(this);" type="button">P</button> </div> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button onclick="setTrueAnswer(this);" type="button" id="clickable">P</button> </div> </div> <button class="addAnswer" onclick="addNewAnswer(1);" type="button">Dodaj odpowiedź</button> </section>');
	$('#clickable').trigger("click");
	assert.equal($('#clickable').hasClass('trueAnswerButtonClicked'), true);
	assert.equal($('#theOtherOne').hasClass('trueAnswerButtonClicked'), true);
});

QUnit.test( "Sprawdz czy checkStringLength dziala poprawnie", function( assert ) {
	QUnit.expect( 2 );
	var shouldBeTrue = checkStringLength("12345678",3);
	var shouldBeFalse = checkStringLength("AA", 4);
	assert.deepEqual(shouldBeTrue, true);
	assert.deepEqual(shouldBeFalse, false);
});

// QUnit.test( "Dodaj jeden do licznika pytania", function( assert ) {
// 	QUnit.expect( 1 );
// 	var counter = iterateQuestionCounter(1);
// 	assert.deepEqual(counter, 2);
// });

QUnit.test( "Sprawdz czy mozna odczytac tytul", function( assert ) {
	QUnit.expect( 2 );
	var fixture = $( "#qunit-fixture" );
	fixture.append("<input id='wentoxNameInput' type='text' value='test' />");
	var inputValue = getQuizTitle();
	assert.deepEqual(inputValue, "test");
	document.getElementById("wentoxNameInput").value = "Tekst";
	inputValue = getQuizTitle();
	assert.deepEqual(inputValue, "Tekst");
});

QUnit.test( "Sprawdz czy mozna odczytac wartosc ze slidera", function( assert ) {
	QUnit.expect( 2 );
	var fixture = $( "#qunit-fixture" );
	fixture.append("<div id='ui-slider'></div>");
	wentoxPerQuestionTimeSlider();
	var sliderVal = getPerQuestionTime();
	assert.deepEqual(sliderVal, 15);
	
	$("#ui-slider").slider('value', 20); 
	sliderVal = getPerQuestionTime();
	assert.deepEqual(sliderVal, 20);
});

QUnit.test( "Sprawdz czy sa pytania", function( assert ) {
	QUnit.expect( 2 );
	var fixture = $( "#qunit-fixture" );
	assert.deepEqual(AreThereAnyQuestions(), false);
	
	fixture.append("<article><section></section></article>");
	assert.deepEqual(AreThereAnyQuestions(), true);
});

QUnit.test( "Sprawdz czy wyszukiwanie pytania dziala", function( assert ) {
	var fixture = $( "#qunit-fixture" );
	fixture.append('<section class="question animated fadeInUp"> <div class="questionTitleContainer"> <input class="questionName" type="text" value="test" placeholder="np. Czy 2+3 to 5?"> <span class="questionCounter">1</span> </div> <div class="questionType"> <p class="questionTypeParagraph">Rodzaj:</p> <div class="questionTypeContainer"> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" checked="checked" value="single"> <label class="questionTypeLabel">jedna odpowiedź</label> </div> <div> <input name="questionTypeNumber:1" class="questionTypeInput" type="radio" value="checkbox"> <label class="questionTypeLabel">wiele odpowiedzi</label> </div> </div> </div> <p class="questionTip">Naciśnij na przycisk "P" aby zaznaczyć poprawną odpowiedź</p> <p class="questionAnswersParagraph">Odpowiedzi:</p> <div class="questionAnswersContainer"> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button class="trueAnswerButtonClicked" onclick="setTrueAnswer(this);" type="button">P</button> </div> <div> <input class="questionAnswerInput" type="text" placeholder="Podaj odpowiedź"> <button onclick="setTrueAnswer(this);" type="button">P</button> </div> </div> <button class="addAnswer" onclick="addNewAnswer(1);" type="button">Dodaj odpowiedź</button> </section>');
	var test = getQuestionName(fixture.html());
	assert.equal(test, "test");
});