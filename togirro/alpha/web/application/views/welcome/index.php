<style>
	.preloader {
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		overflow: hidden;
		background: #fff;
		/* background: rgba(230, 227, 210, 1); */
		/* background: #6d757f; */
		z-index: 1001;
	}
	@keyframes scale1 {
		/* 0%   { height: 6.8vh; }
		25%  { height: 13.6vh; }
		50%  { height: 20.4vh; }
		75%  { height: 27.2vh; }
		100% { height: 34vh; } */
		0% { opacity: 0; }
		25% { opacity: 0.25; }
		50% { opacity: 0.5; }
		75% { opacity: 0.75; }
		100% { opacity: 1; }
	}
	@keyframes scale2 {
		/* 0%   { height: 6.8vh; width: 6.8vh; }
		25%  { height: 13.6vh; width: 13.6vh; }
		50%  { height: 20.4vh; width: 20.4vh; }
		75%  { height: 27.2vh; width: 27.2vh; }
		100% { height: 34vh; width: 34vh; } */
		0% { opacity: 0; }
		25% { opacity: 0.25; }
		50% { opacity: 0.5; }
		75% { opacity: 0.75; }
		100% { opacity: 1; }
	}
	.preloader-img {
		position: absolute;
		left: 50.2vw;
		top: 50vh;
		transform: translate(-50%, -50%);
		z-index: 1;
		height: 34vh;
		animation: scale1 1s linear;
	}
	.ring-container {
		animation: scale2 1s linear;
		width: 34vh;
		height: 34vh;
		margin: 0 auto;
		position: relative;
		position: absolute;
		left: 50vw;
		top: 50vh;
		transform: translate(-50%, -50%);
		z-index: 0;
	}
	.ring {
		animation-name: pulse;
		animation-duration: 2s;
		animation-timing-function: linear;
		animation-iteration-count: infinite;
		animation-direction: normal;
		animation-fill-mode: forwards;
		background-color: #cecece;
		border-radius: 50%;
		position: absolute;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
	}
	.ring:nth-child(1) {
		animation-delay: 0s;
	}
	.ring:nth-child(2) {
		animation-delay: 1s;
	}
	.ring:nth-child(3) {
		animation-delay: 1.5s;
	}
	@keyframes pulse {
	0% {
		opacity: 0;
		transform: scale(1, 1);
	}
	50% {
		opacity: 1.25;
	}
	100% {
		opacity: 0;
		transform: scale(1.5, 1.5);
	}
	}
</style>
<script type="text/javascript">
	$("html").css("overflow", 'hidden');
	$("html").css("margin", '0');
</script>
<div class="preloader">
	<img src="assets/templates/teacher2020/images/preloader-min.png" class="preloader-img">
	<div class="ring-container">
		<div class="ring"></div>
		<div class="ring"></div>
		<div class="ring"></div>
	</div>
</div>
{header}

    <div class="site-content agency-site-content">

		  <section id="home" class="hero-section hero-agency">            
				<div class="main-baner">
					
				</div>            
        </section>
		{roadmap_main}
        {roadmap1}
		{roadmap2}
		{org}
        <div class="container content2">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="service-item style-two aos-init" data-aos="fade-up" data-aos-duration="1500">
						<div class="services-content">
							<h3><img src="assets/templates/teacher2020/images/icons/4.png" alt="thumb"> Архив материалов</h3>
							<ul>
								{docs}				
								<li><a href="assets/uploads/participant_doc/{doc_url}"><b>{doc_day} августа.</b> {doc_name}</a></li>
								{/docs}
							</ul>
						</div></div>
				</div>
			</div>
		</div>
				
        <section id="services" class="content1 services-section services-bg style-two pt-30 pb-150" style="background-image:url(&#39;assets/templates/teacher2020/images/services-bg.png&#39;)">
            <div class="container">
                <div class="row justify-content-between no-gutters">
				
					<div class="col-lg-12">                        
                        <div class="section-header style-seven services-left aos-init" data-aos="fade-zoom-in" data-aos-duration="1000">
                            <h4 class="title-script text-justify w-100"></h4>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="services-items-list">
							<div class="row no-gutters">
                                <!--
                                <div class="col-lg-12 col-md-12">
                                    <div class="service-item style-two aos-init" data-aos="fade-up" data-aos-duration="1200">
                                        <div class="services-content">
                                            <h3><img src="assets/templates/teacher2020/images/icons/2.png" alt="thumb"> Медиатека</h3>
                                            {video}
                                            <dt class="col-sm-12"><a href="{video_url}" target="_blank"><b>{category}.</b> {video_name}</a></dt>
                                            {/video}
											

                                        </div> 
                                    </div>
                                    -->
                                </div><!--~./ end services item ~-->
							</div>
                            <div class="row no-gutters">
                                <!--~~~~~ Start Services Item ~~~~~--> 
								<div class="col-lg-12 col-md-12">
									<div class="service-item style-two aos-init" data-aos="fade-up" data-aos-duration="1500">
                                        <div class="services-content">
                                            <h3><img src="assets/templates/teacher2020/images/icons/4.png" alt="thumb"> Документация</h3>
											<ul id="documentation" class="hidden" style="display: none;">
												<li style="margin-bottom: 5px;">
													<a href="https://togirro.ru/assets/files/2021/dar/pedagog_goda_2021/pol_konkursa_10.03.2021_118-1.PDF" title="Приказ ДОин ТО от 10.03.2021 г. №118-1/ОД" target="_blank">
														Приказ ДОин ТО от 10.03.2021 г. №118-1/ОД "О внесении изменения в приказ от 04.02.2019 г. №43/ОД "О проведении областного конкурса профессионального мастерства  "Педагог года Тюменской области", положение конкурса
													</a>
												</li>
												<li style="margin-bottom: 5px;">
													<a href="https://togirro.ru/assets/files/2021/dar/pedagog_goda_2021/formy_pedagog_goda_2021.docx" title="Формы документов для участия в конкурсе" target="_blank">
														Формы документов для участия в конкурсе
													</a>
												</li>
												<li style="margin-bottom: 5px;" class="last">
													<a href="https://togirro.ru/assets/files/2021/dar/yunior/inf_pismo_pedagog_goda_o_predostavlenii_informacii.pdf" title="Информационное письмо о предоставлении информации от муниципальных органов управлением образованием" target="_blank">
														Информационное письмо о предоставлении информации от муниципальных органов управлением образованием
													</a>
												</li>
											</ul>
                                        </div>
										<a href="#" id="toggle-documentation" class="collapse-button btn-show" title="Показать"><span class="fa fa-angle-double-down"></span></a>
                                    </div>
								</div>
								<div class="col-lg-12 col-md-12">
									<div class="service-item style-two aos-init" data-aos="fade-up" data-aos-duration="1500">

                                        <div id="expert-section" class="services-content">
                                            <h3><img src="assets/templates/teacher2020/images/icons/3.png" alt="thumb"> Жюри</h3>
											<div id="expert_control">						
												<button id="ex_all" class="btn btn-primary btn-gradient-1 expert-category" value="-1">Все</button>
												<button class="btn btn-primary btn-gradient0 expert-category" value="0">Учитель года</button>
												<button class="btn btn-primary btn-gradient1 expert-category" value="1">Педагогический дебют (учитель)</button>
												<button class="btn btn-primary btn-gradient2 expert-category" value="2">Воспитатель года</button>
												<button class="btn btn-primary btn-gradient8 expert-category" value="8">Педагогический дебют (воспитатель)</button>
												<button class="btn btn-primary btn-gradient3 expert-category" value="3">Педагог-психолог года</button>
												<button class="btn btn-primary btn-gradient4 expert-category" value="4">Учитель-дефектолог года</button>
												<button class="btn btn-primary btn-gradient7 expert-category" value="7">Мастер года</button>
												<button class="btn btn-primary btn-gradient6 expert-category" value="6">Классный руководитель года</button>
												<button class="btn btn-primary btn-gradient5 expert-category" value="5">Молодой руководитель года</button>
											</div>
											<a href="#" id="toogle-expert" class="collapse-button btn-show" title="Показать"><span class="fa fa-angle-double-down"></span></a>
                                            <div id="experts" class="card-columns" style="display:none">
											
												{expert}							
												<div class="card card-expert border p-lg-4 card-cat-{expert_category}  block-collapse" category="{expert_category}">
													<div class="card-body">
													{expert_img}
														<span class="badge badge-primary badge-primary-soft btn-gradient{expert_category}" title="Оценка в номинации">{expert_category_name}</span>
														<h3 class="font-weight-normal mt-1" title="{expert_id}"><span class="d-block text-dark" >{expert_name}</span></h3>
													</div>
													<div class="card-footer bg-transparent px-1 border-0 block-collapse-div">
														<p>{expert_info}</p>
													</div>
												</div>
												{/expert}
											</div>
											
                                        </div>
                                    </div>
								</div>	
							</div>
                                
                        </div>
							<div id="participant_add0" class="row no-gutters" style="display:none">
								<div class="col-lg-12 col-md-12">
									<div class="service-item style-two aos-init" data-aos="fade-up" data-aos-duration="1500">
                                        <div class="services-content">
                                            <h3>Предметное жюри в номинациях «Учитель года»</h3>
                                            <ul>
												<li style="margin-bottom: 5px;"><b>Бакиева Ольга Афанасьевна</b><p> доцент кафедры искусств ФГАОУ ВО «Тюменский государственный университет» Институт педагогики и психологии, кандидат педагогических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Каткова Ольга Анатольевна</b><p> заведующая кафедрой естественно-математических дисциплин ГАОУ ТО ДПО «Тюменский областной государственный институт развития регионального образования», кандидат педагогических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Менчинская Елена Анатольевна</b><p> заведующая кафедрой дошкольного и начального общего образования ГАОУ ТО ДПО «Тюменский областной государственный институт развития регионального образования», кандидат педагогических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Симон Наталья Александровна</b><p> доцент кафедры дошкольного и начального общего образования ГАОУ ТО ДПО «Тюменский областной государственный институт развития регионального образования», кандидат педагогических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Кирамова Халида Халидулловна</b><p> учитель татарского языка и литературы, русского языка и литературы МАОУ СОШ № 52 города Тюмени, кандидат филологических наук, Почетный работник в сфере образования РФ</p></li>
												<li style="margin-bottom: 5px;"><b>Смолин Александр Михайлович</b><p> учитель физики ФГКОУ «Тюменское президентское кадетское училище», победитель конкурса «Учитель года Тюменской области-2014»</p></li>
												<li style="margin-bottom: 5px;"><b>Трифонов Максим Иванович</b><p> заместитель директора, учитель математики МАОУ гимназии № 12 г. Тюмени, победитель конкурса «Учитель года Тюменской области-2016»</p></li>
												<li style="margin-bottom: 5px;"><b>Чекардовская Ирина Александровна</b><p> доцент кафедры транспорта углеводородных ресурсов Института транспорта ФГБОУ ВО «Тюменский индустриальный университет», директор образовательного центра «ИрИС», кандидат технических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Шастунова Ульяна Юрьевна</b><p> доцент кафедры прикладной и технической физики Физико-технического института ФГАОУ ВО «Тюменский государственный университет», кандидат физико-математических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Самусенко Елена Анатольевна</b><p> заведующая кафедрой социально-гуманитарных дисциплин ГАОУ ТО ДПО «Тюменский областной государственный институт развития регионального образования»</p></li>
											</ul>
                                        </div>
                                    </div>
								</div>
                            </div>
							<div id="participant_add1" class="row no-gutters" style="display:none">
								<div class="col-lg-12 col-md-12">
									<div class="service-item style-two aos-init" data-aos="fade-up" data-aos-duration="1500">
                                        <div class="services-content">
                                            <h3>Предметное жюри в номинации «Педагогический дебют (учитель)»</h3>
                                            <ul>
												<li style="margin-bottom: 5px;"><b>Белявская Юлия Евгеньевна</b><p> доцент кафедры социально-гуманитарных дисциплин ГАОУ ТО ДПО «Тюменский областной государственный институт развития регионального образования», кандидат исторических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Ионина Наталья Геннадьевна</b><p> доцент кафедры естественно-математических дисциплин ГАОУ ТО ДПО «Тюменский областной государственный институт развития регионального образования», кандидат биологических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Бадрызлова Ольга Владимировна</b><p> доцент кафедры немецкой филологии Института социально-гуманитарных наук ФГАОУ ВО «Тюменский государственный университет», кандидат филологических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Бакиева Ольга Афанасьевна</b><p> доцент кафедры искусств ФГАОУ ВО «Тюменский государственный университет» Институт педагогики и психологии, кандидат педагогических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Ерохин Виталий Викторович</b><p> учитель физики, МАОУ Средняя общеобразовательная школа № 88 г. Тюмени, специальный приз за содействие гражданскому воспитанию и культуры мира среди обучающихся, победитель областного конкурса «Педагог года – 2019», лауреат конкурса «Учитель года России - 2019»</p></li>
												<li style="margin-bottom: 5px;"><b>Камитова Анастасия Ивановна</b><p> учитель МАОУ гимназия № 5 г. Тюмени, победитель областного конкурса «Учитель года Тюменской области - 2020», эксперт «WorldskillsRussia» по компетенции «Преподавание в начальных классах»</p></li>
												<li style="margin-bottom: 5px;"><b>Прудаева Ирина Владимировна</b><p> и.о. заместителя руководителя Центра непрерывного повышения профессионального мастерства педагогических работников образования, победитель конкурса лучших учителей Тюменской области - 2017</p></li>
												<li style="margin-bottom: 5px;"><b>Трифонов Максим Иванович</b><p> заместитель директора, учитель математики МАОУ гимназии № 12 г. Тюмени, победитель конкурса «Учитель года Тюменской области-2016»</p></li>
												<li style="margin-bottom: 5px;"><b>Чекардовская Ирина Александровна</b><p> доцент кафедры транспорта углеводородных ресурсов Института транспорта ФГБОУ ВО «Тюменский индустриальный университет», директор образовательного центра «ИрИС», кандидат технических наук</p></li>
												<li style="margin-bottom: 5px;"><b>Самусенко Елена Анатольевна</b><p> заведующая кафедрой социально-гуманитарных дисциплин ГАОУ ТО ДПО «Тюменский областной государственный институт развития регионального образования»</p></li>
											</ul>
                                        </div>
                                    </div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            
        </section>
        
        <section id="testimonials" class="content1 testimonial-section style-two bg-image  pb-150" style="background-image:url('assets/templates/teacher2020/images/map.png')">
            <div class="container">
			
                <div class="row justify-content-center">
					<div class="col-lg-12">
                        
                        <div class="section-header style-seven services-left aos-init aos-animate" data-aos="fade-zoom-in" data-aos-duration="1000">
                            <h4 class="title-script text-justify w-100">участники</h4>
                        </div>
                    </div>	
									
						<div id="map" class="col-lg-8 col-sm-12">
							<div id="mapBaloon" style="display:none">Район</div>
							{map}
						</div>
					
					
					<div class="col-lg-12">
						<div id="regions">
							<div id="dd" class="wrapper-dropdown-3 " tabindex="1">
								
								<span>Выберите район...</span>
								<ul class="dropdown">
									<li><a href="#">Абатский район</a></li>
									<li><a href="#">Армизонский район</a></li>
									<li><a href="#">Аромашевский район</a></li>
									<li><a href="#">Бердюжский район</a></li>
									<li><a href="#">Вагайский район</a></li>
									<li><a href="#">Викуловский район</a></li>
									<li><a href="#">Голышмановский район</a></li>
									<li><a href="#">Заводоуковский район</a></li>
									<li><a href="#">Исетский район</a></li>
									<li><a href="#">Ишимский район</a></li>
									<li><a href="#">Казанский район</a></li>
									<li><a href="#">Нижнетавдинский район</a></li>
									<li><a href="#">Омутинский район</a></li>
									<li><a href="#">Сладковский район</a></li>
									<li><a href="#">Сорокинский район</a></li>
									<li><a href="#">Тобольский район</a></li>
									<li><a href="#">Тюменский район</a></li>
									<li><a href="#">Уватский район</a></li>
									<li><a href="#">Упоровский район</a></li>
									<li><a href="#">Юргинский район</a></li>
									<li><a href="#">Ялуторовский район</a></li>
									<li><a href="#">Ярковский район</a></li>
									<li><a href="#">г. Ишим</a></li>
									<li><a href="#">г. Тобольск</a></li>
									<li><a href="#">г. Тюмень</a></li>
									<li><a href="#">г. Ялуторовск</a></li>
								</ul>
							</div>
						</div>
					</div>					
					<div class="col-lg-12">
						<div id="map_control">						
							<button class="btn btn-primary btn-gradient0 map-change-category" value="0">Учитель года</button>
							<button class="btn btn-primary btn-gradient1 map-change-category" value="1">Педагогический дебют (учитель)</button>
							<button class="btn btn-primary btn-gradient2 map-change-category" value="2">Воспитатель года</button>
							<button class="btn btn-primary btn-gradient8 map-change-category" value="8">Педагогический дебют (воспитатель)</button>
							<button class="btn btn-primary btn-gradient3 map-change-category" value="3">Педагог-психолог года</button>
							<button class="btn btn-primary btn-gradient4 map-change-category" value="4">Учитель-дефектолог года</button>
							<button class="btn btn-primary btn-gradient7 map-change-category" value="7">Мастер года</button>
							<button class="btn btn-primary btn-gradient6 map-change-category" value="6">Классный руководитель года</button>
							<button class="btn btn-primary btn-gradient5 map-change-category" value="5">Молодой руководитель года</button>
						</div>
					</div>
						
						
					<!--
					<a href="#" id="toogle-participant" class="collapse-button btn-show" title="Показать"><span class="fa fa-angle-double-down"></span></a>
					-->
					<div class="col-lg-12">							
						<div id="participants" class="card-columns">
												
						{participant}								
							  <div style="display:none" class="card card-participant border p-lg-3 card-cat-{participant_category} block-collapse" region="{participant_location_id}" category="{participant_category}">
								<div class="card-body">
								<div class="single-testimonial text-center"><div class="client-thumb" style="margin:0">
								<img src="{participant_photo}" alt="thumb">
								</div></div> 
								  <span class="badge badge-primary badge-primary-soft btn-gradient{participant_category}" title="Номинация">{participant_category_name}</span>
								  <h3 class="font-weight-normal mt-1 "><span class="d-block text-dark" >{participant_name}</span></h3>
								  
								</div>
								<div class="card-footer bg-transparent px-1 border-0 block-collapse-div">
								  <p>{participant_institution}</p>
								  <p><a href="{participant_site}">{participant_site}</a></p>
								  <p>{participant_location}</p>
								  <p><a class="btn btn-primary btn-gradient{participant_category} cat-btn" href="welcome/participant/{participant_id}">Подробнее</a></p>
								</div>
							  </div>
							
							{/participant}
						</div>
					</div>
                </div>
            </div>
        </section>
        
        
        {map2}
        <section id="contact" class="content1 content2 contact-form-section ptb-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="contact-form-area">
                            <div class="section-header style-seven aos-init" data-aos="fade-zoom-in" data-aos-duration="1200">
                                <h4 class="title-script">техническая поддержка</h4>
                                <h2 class="section-title">Если у вас возникли вопросы, напишите нам на электронную почту: <a href="mailto:forum@togirro.ru">forum@togirro.ru</a> или позвоните: <br>+7 905 820 71 81</h2>
                            </div>
                            <form style="display:none" class="form aos-init" id="contact-form" data-aos="fade-zoom-in" data-aos-duration="1200">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                        <input class="form-controller" id="form-name" name="name" placeholder="Имя" type="text">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input class="form-controller" id="form-email" name="email" placeholder="email" type="email">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea class="form-controller" id="form-message" name="message" placeholder="Сообщение"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="btn-submit-area">
                                            <div class="row align-items-center">
                                                
                                                <div class="col-lg-6 text-right">
                                                    <button type="submit" class="btn btn-default btn-send">написать</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="thumbnail-area">
                            <figure class="thumbnail">
                                <img src="assets/templates/teacher2020/images/pathern3.png" alt="img">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </div>
    <!-- End site content -->
      
{footer}