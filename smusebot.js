// Chat state
const state = {
    language: 'en',
    museum: 'victoria',
    context: 'greeting',
    isTyping: false,
    bookingData: {
        date: '',
        time: '',
        visitors: 1,
        name: '',
        email: '',
        phone: '',
        museum: ''
    }
};

// Museum data
const museums = {
    victoria: {
        name: "Victoria Memorial",
        image: "https://upload.wikimedia.org/wikipedia/commons/3/3d/Victoria_Memorial%2C_Kolkata.jpg",
        info: {
            en: "Victoria Memorial is a large marble building in Kolkata, India, which was built between 1906 and 1921. It is dedicated to the memory of Queen Victoria, Empress of India from 1876 to 1901. The memorial has 25 galleries including the royal gallery, sculpture gallery, and the Calcutta gallery.",
            hi: "विक्टोरिया मेमोरियल कोलकाता, भारत में एक बड़ी संगमरमर की इमारत है, जिसे 1906 और 1921 के बीच बनाया गया था। यह रानी विक्टोरिया, 1876 से 1901 तक भारत की साम्राज्ञी की याद में समर्पित है। स्मारक में रॉयल गैलरी, मूर्तिकला गैलरी और कलकत्ता गैलरी सहित 25 गैलरी हैं।",
            bn: "ভিক্টোরিয়া মেমোরিয়াল কলকাতা, ভারতে একটি বড় মার্বেল ভবন, যা 1906 এবং 1921 সালের মধ্যে নির্মিত হয়েছিল। এটি রানী ভিক্টোরিয়া, 1876 থেকে 1901 সাল পর্যন্ত ভারতের সম্রাজ্ঞীর স্মৃতির উদ্দেশ্যে উৎসর্গীকৃত। স্মারকে রয়্যাল গ্যালারি, ভাস্কর্য গ্যালারি এবং কলকাতা গ্যালারি সহ 25টি গ্যালারি রয়েছে।"
        },
        hours: {
            en: "Victoria Memorial is open from Tuesday to Sunday, 10:00 AM to 5:00 PM. It is closed on Mondays and national holidays.",
            hi: "विक्टोरिया मेमोरियल मंगलवार से रविवार, सुबह 10:00 बजे से शाम 5:00 बजे तक खुला है। यह सोमवार और राष्ट्रीय अवकाशों पर बंद रहता है।",
            bn: "ভিক্টোরিয়া মেমোরিয়াল মঙ্গলবার থেকে রবিবার, সকাল 10:00 টা থেকে বিকেল 5:00 টা পর্যন্ত খোলা থাকে। এটি সোমবার এবং জাতীয় ছুটির দিনে বন্ধ থাকে।"
        },
        location: {
            en: "Victoria Memorial is located in Kolkata, West Bengal, India.",
            hi: "विक्टोरिया मेमोरियल कोलकाता, पश्चिम बंगाल, भारत में स्थित है।",
            bn: "ভিক্টোরিয়া মেমোরিয়াল কলকাতা, পশ্চিমবঙ্গ, ভারতে অবস্থিত।"
        }
    },
    national: {
        name: "National Museum",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/National_Museum%2C_New_Delhi.jpg/1280px-National_Museum%2C_New_Delhi.jpg",
        info: {
            en: "The National Museum in New Delhi is one of the largest museums in India. Established in 1949, it houses various artifacts from prehistoric era to modern works of art. It has over 200,000 works of art, both of Indian and foreign origin, covering over 5,000 years of cultural heritage.",
            hi: "नई दिल्ली में राष्ट्रीय संग्रहालय भारत के सबसे बड़े संग्रहालयों में से एक है। 1949 में स्थापित, इसमें प्रागैतिहासिक युग से लेकर आधुनिक कला कृतियों तक विभिन्न कलाकृतियां हैं। इसमें भारतीय और विदेशी मूल के 200,000 से अधिक कला कार्य हैं, जो 5,000 से अधिक वर्षों की सांस्कृतिक विरासत को कवर करते हैं।",
            bn: "নয়াদিল্লিতে জাতীয় জাদুঘর ভারতের বৃহত্তম জাদুঘরগুলির মধ্যে একটি। 1949 সালে প্রতিষ্ঠিত, এটি প্রাগৈতিহাসিক যুগ থেকে আধুনিক শিল্পকর্ম পর্যন্ত বিভিন্ন নিদর্শন রয়েছে। এতে ভারতীয় ও বিদেশী উভয় উৎসের 200,000 এরও বেশি শিল্পকর্ম রয়েছে, যা 5,000 বছরেরও বেশি সাংস্কৃতিক ঐতিহ্য কভার করে।"
        },
        hours: {
            en: "The National Museum is open from Tuesday to Sunday, 10:00 AM to 6:00 PM. It is closed on Mondays and national holidays.",
            hi: "राष्ट्रीय संग्रहालय मंगलवार से रविवार, सुबह 10:00 बजे से शाम 6:00 बजे तक खुला है। यह सोमवार और राष्ट्रीय अवकाशों पर बंद रहता है।",
            bn: "জাতীয় জাদুঘর মঙ্গলবার থেকে রবিবার, সকাল 10:00 টা থেকে সন্ধ্যা 6:00 টা পর্যন্ত খোলা থাকে। এটি সোমবার এবং জাতীয় ছুটির দিনে বন্ধ থাকে।"
        },
        location: {
            en: "The National Museum is located in New Delhi, India.",
            hi: "राष्ट्रीय संग्रहालय नई दिल्ली, भारत में स्थित है।",
            bn: "জাতীয় জাদুঘর নয়াদিল্লি, ভারতে অবস্থিত।"
        }
    },
    salarjung: {
        name: "Salar Jung Museum",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Salar_Jung_Museum_Hyderabad.jpg/1280px-Salar_Jung_Museum_Hyderabad.jpg",
        info: {
            en: "The Salar Jung Museum in Hyderabad is one of the three National Museums of India. It has a collection of sculptures, paintings, carvings, textiles, manuscripts, ceramics, metallic artifacts, carpets, clocks, and furniture from Japan, China, Burma, Nepal, India, Persia, Egypt, Europe, and North America.",
            hi: "हैदराबाद में सालार जंग संग्रहालय भारत के तीन राष्ट्रीय संग्रहालयों में से एक है। इसमें जापान, चीन, बर्मा, नेपाल, भारत, फारस, मिस्र, यूरोप और उत्तरी अमेरिका से मूर्तियों, चित्रों, नक्काशी, कपड़े, पांडुलिपियों, मिट्टी के बर्तनों, धातु के कलाकृतियों, कालीनों, घड़ियों और फर्नीचर का संग्रह है।",
            bn: "হায়দরাবাদের সালার জং জাদুঘর ভারতের তিনটি জাতীয় জাদুঘরের মধ্যে একটি। এতে জাপান, চীন, বার্মা, নেপাল, ভারত, পারস্য, মিশর, ইউরোপ এবং উত্তর আমেরিকা থেকে ভাস্কর্য, চিত্রকর্ম, খোদাই, বস্ত্র, পাণ্ডুলিপি, সিরামিক, ধাতব নিদর্শন, কার্পেট, ঘড়ি এবং আসবাবপত্রের সংগ্রহ রয়েছে।"
        },
        hours: {
            en: "The Salar Jung Museum is open from Saturday to Thursday, 10:00 AM to 5:00 PM. It is closed on Fridays.",
            hi: "सालार जंग संग्रहालय शनिवार से गुरुवार, सुबह 10:00 बजे से शाम 5:00 बजे तक खुला है। यह शुक्रवार को बंद रहता है।",
            bn: "সালার জং জাদুঘর শনিবার থেকে বৃহস্পতিবার, সকাল 10:00 টা থেকে বিকেল 5:00 টা পর্যন্ত খোলা থাকে। এটি শুক্রবার বন্ধ থাকে।"
        },
        location: {
            en: "The Salar Jung Museum is located in Hyderabad, Telangana, India.",
            hi: "सालार जंग संग्रहालय हैदराबाद, तेलंगाना, भारत में स्थित है।",
            bn: "সালার জং জাদুঘর হায়দরাবাদ, তেলেঙ্গানা, ভারতে অবস্থিত।"
        }
    },
    csmvs: {
        name: "Chhatrapati Shivaji Maharaj Vastu Sangrahalaya",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Mumbai_03-2016_30_Chhatrapati_Shivaji_Maharaj_Vastu_Sangrahalaya.jpg/1280px-Mumbai_03-2016_30_Chhatrapati_Shivaji_Maharaj_Vastu_Sangrahalaya.jpg",
        info: {
            en: "The Chhatrapati Shivaji Maharaj Vastu Sangralaya (CSMVS), formerly known as the Prince of Wales Museum of Western India, is one of the premier art and history museums in India. Established in 1922, it houses approximately 50,000 artifacts and art objects from ancient India, foreign lands, and various periods of Indian history.",
            hi: "छत्रपति शिवाजी महाराज वस्तु संग्रहालय (CSMVS), जिसे पहले पश्चिमी भारत के प्रिंस ऑफ वेल्स संग्रहालय के नाम से जाना जाता था, भारत के प्रमुख कला और इतिहास संग्रहालयों में से एक है। 1922 में स्थापित, इसमें प्राचीन भारत, विदेशी भूमि और भारतीय इतिहास के विभिन्न कालों से लगभग 50,000 कलाकृतियां और कला वस्तुएं हैं।",
            bn: "ছত্রপতি শিবাজী মহারাজ বস্তু সংগ্রহালয় (CSMVS), যা আগে পশ্চিম ভারতের প্রিন্স অফ ওয়েলস জাদুঘর নামে পরিচিত ছিল, ভারতের অন্যতম প্রধান শিল্প ও ইতিহাস জাদুঘর। 1922 সালে প্রতিষ্ঠিত, এতে প্রাচীন ভারত, বিদেশী ভূমি এবং ভারতীয় ইতিহাসের বিভিন্ন সময়কাল থেকে প্রায় 50,000 নিদর্শন ও শিল্পবস্তু রয়েছে।"
        },
        hours: {
            en: "CSMVS is open every day from 10:15 AM to 6:00 PM.",
            hi: "CSMVS हर दिन सुबह 10:15 बजे से शाम 6:00 बजे तक खुला रहता है।",
            bn: "CSMVS প্রতিদিন সকাল 10:15 টা থেকে সন্ধ্যা 6:00 টা পর্যন্ত খোলা থাকে।"
        },
        location: {
            en: "CSMVS is located in Mumbai, Maharashtra, India.",
            hi: "CSMVS मुंबई, महाराष्ट्र, भारत में स्थित है।",
            bn: "CSMVS মুম্বাই, মহারাষ্ট্র, ভারতে অবস্থিত।"
        }
    },
    calico: {
        name: "Calico Museum of Textiles",
        image: "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Calico_Museum_of_Textiles_Ahmedabad.jpg/1280px-Calico_Museum_of_Textiles_Ahmedabad.jpg",
        info: {
            en: "The Calico Museum of Textiles in Ahmedabad is one of the most celebrated textile museums in the world. Founded in 1949, it houses a diverse collection of Indian textiles and artifacts spanning five centuries. The museum showcases court textiles, regional embroideries, tie-dye textiles, and religious textiles.",
            hi: "अहमदाबाद में कैलिको टेक्सटाइल संग्रहालय दुनिया के सबसे प्रसिद्ध कपड़ा संग्रहालयों में से एक है। 1949 में स्थापित, इसमें पांच शताब्दियों तक फैले भारतीय कपड़ों और कलाकृतियों का विविध संग्रह है। संग्रहालय में दरबारी कपड़े, क्षेत्रीय कढ़ाई, टाई-डाई कपड़े और धार्मिक कपड़े प्रदर्शित हैं।",
            bn: "আহমেদাবাদের ক্যালিকো টেক্সটাইল জাদুঘর বিশ্বের সবচেয়ে বিখ্যাত টেক্সটাইল জাদুঘরগুলির মধ্যে একটি। 1949 সালে প্রতিষ্ঠিত, এতে পাঁচ শতাব্দী জুড়ে ভারতীয় বস্ত্র ও নিদর্শনের বৈচিত্র্যময় সংগ্রহ রয়েছে। জাদুঘরে রাজদরবারের বস্ত্র, আঞ্চলিক সেলাই, টাই-ডাই বস্ত্র এবং ধর্মীয় বস্ত্র প্রদর্শিত হয়।"
        },
        hours: {
            en: "The Calico Museum is open from Tuesday to Sunday, with guided tours at 10:30 AM and 2:30 PM. Prior booking is required. It is closed on Mondays.",
            hi: "कैलिको संग्रहालय मंगलवार से रविवार तक खुला है, सुबह 10:30 बजे और दोपहर 2:30 बजे गाइडेड टूर के साथ। पूर्व बुकिंग आवश्यक है। यह सोमवार को बंद रहता है।",
            bn: "ক্যালিকো জাদুঘর মঙ্গলবার থেকে রবিবার পর্যন্ত খোলা থাকে, সকাল 10:30 টায় এবং দুপুর 2:30 টায় গাইডেড টুর সহ। আগে থেকে বুকিং করা আবশ্যক। এটি সোমবার বন্ধ থাকে।"
        },
        location: {
            en: "The Calico Museum is located in Ahmedabad, Gujarat, India.",
            hi: "कैलिको संग्रहालय अहमदाबाद, गुजरात, भारत में स्थित है।",
            bn: "ক্যালিকো জাদুঘর আহমেদাবাদ, গুজরাট, ভারতে অবস্থিত।"
        }
    }
};

// Translations
const translations = {
    en: {
        greeting: "👋 Hello! I'm your museum assistant. How can I help you today?",
        museumSelect: "Please select a museum you'd like to learn about:",
        options: "You can ask about:",
        option1: "Museum Info",
        option2: "Book Tickets",
        option3: "Opening Hours",
        option4: "Location",
        languageChanged: "Language changed to English",
        museumChanged: "You are now viewing information about",
        bookingIntro: "Let's book your tickets. Please fill in the details:",
        bookingConfirm: "Thank you! Your booking is confirmed for",
        askMore: "What else would you like to know about this museum?",
        notUnderstood: "I'm sorry, I didn't understand that. Could you try again or select one of the options?",
        visitors: "visitors",
        on: "on",
        at: "at"
    },
    hi: {
        greeting: "👋 नमस्ते! मैं आपका संग्रहालय सहायक हूँ। मैं आपकी कैसे मदद कर सकता हूँ?",
        museumSelect: "कृपया एक संग्रहालय चुनें जिसके बारे में आप जानना चाहते हैं:",
        options: "आप पूछ सकते हैं:",
        option1: "संग्रहालय जानकारी",
        option2: "टिकट बुक करें",
        option3: "खुलने का समय",
        option4: "स्थान",
        languageChanged: "भाषा हिंदी में बदल गई है",
        museumChanged: "अब आप इसके बारे में जानकारी देख रहे हैं",
        bookingIntro: "आइए आपके टिकट बुक करें। कृपया विवरण भरें:",
        bookingConfirm: "धन्यवाद! आपकी बुकिंग की पुष्टि हो गई है",
        askMore: "आप इस संग्रहालय के बारे में और क्या जानना चाहेंगे?",
        notUnderstood: "मुझे खेद है, मैं समझ नहीं पाया। क्या आप फिर से कोशिश कर सकते हैं या किसी विकल्प का चयन कर सकते हैं?",
        visitors: "आगंतुक",
        on: "पर",
        at: "बजे"
    },
    bn: {
        greeting: "👋 হ্যালো! আমি আপনার জাদুঘর সহকারী। আমি আপনাকে কীভাবে সাহায্য করতে পারি?",
        museumSelect: "অনুগ্রহ করে একটি জাদুঘর নির্বাচন করুন যার সম্পর্কে আপনি জানতে চান:",
        options: "আপনি জিজ্ঞাসা করতে পারেন:",
        option1: "জাদুঘর তথ্য",
        option2: "টিকিট বুক করুন",
        option3: "খোলার সময়",
        option4: "অবস্থান",
        languageChanged: "ভাষা বাংলায় পরিবর্তন করা হয়েছে",
        museumChanged: "আপনি এখন এই সম্পর্কে তথ্য দেখছেন",
        bookingIntro: "আসুন আপনার টিকিট বুক করি। অনুগ্রহ করে বিবরণ পূরণ করুন:",
        bookingConfirm: "ধন্যবাদ! আপনার বুকিং নিশ্চিত করা হয়েছে",
        askMore: "আপনি এই জাদুঘর সম্পর্কে আর কী জানতে চান?",
        notUnderstood: "দুঃখিত, আমি বুঝতে পারিনি। আপনি কি আবার চেষ্টা করতে পারেন বা কোনও বিকল্প নির্বাচন করতে পারেন?",
        visitors: "দর্শনার্থী",
        on: "তারিখে",
        at: "সময়ে"
    }
};

// DOM elements
const chatIcon = document.getElementById('chat-icon');
const chatContainer = document.getElementById('chat-container');
const closeChat = document.getElementById('close-chat');
const chatMessages = document.getElementById('chat-messages');
const userInput = document.getElementById('user-input');
const sendButton = document.getElementById('send-button');
const languageSelector = document.getElementById('language-selector');
const museumSelector = document.getElementById('museum-selector');
const museumTitle = document.getElementById('museum-title');

// Ensure the title always displays "MUSE-BOT🤖"
museumTitle.textContent = "Muse-Bot";

// Toggle chat window
chatIcon.addEventListener('click', () => {
    chatContainer.classList.add('active');
    if (chatMessages.children.length === 0) {
        initChat();
    }
});

closeChat.addEventListener('click', () => {
    chatContainer.classList.remove('active');
});

// Initialize chat
function initChat() {
    showTypingIndicator();

    setTimeout(() => {
        removeTypingIndicator();
        addBotMessage(translations[state.language].greeting);

        setTimeout(() => {
            showTypingIndicator();
            setTimeout(() => {
                removeTypingIndicator();
                addBotMessageWithOptions(translations[state.language].options);
            }, 700);
        }, 500);
    }, 700);
}

// Show typing indicator
function showTypingIndicator() {
    if (state.isTyping) return;

    state.isTyping = true;
    const typingDiv = document.createElement('div');
    typingDiv.classList.add('typing-indicator');
    typingDiv.id = 'typing-indicator';

    for (let i = 0; i < 3; i++) {
        const dot = document.createElement('span');
        typingDiv.appendChild(dot);
    }

    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Remove typing indicator
function removeTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
        state.isTyping = false;
    }
}

// Add bot message to chat
function addBotMessage(text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', 'bot-message');
    messageDiv.textContent = text;

    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Add bot message with options
function addBotMessageWithOptions(text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', 'bot-message');
    messageDiv.textContent = text;

    const optionsContainer = document.createElement('div');
    optionsContainer.classList.add('options-container');

    // Add option buttons
    const options = [
        { text: translations[state.language].option1, value: 'info', icon: 'fa-info-circle' },
        { text: translations[state.language].option2, value: 'booking', icon: 'fa-ticket-alt' },
        { text: translations[state.language].option3, value: 'hours', icon: 'fa-clock' },
        { text: translations[state.language].option4, value: 'location', icon: 'fa-map-marker-alt' }
    ];

    options.forEach(option => {
        const button = document.createElement('button');
        button.classList.add('option-button');

        const icon = document.createElement('i');
        icon.className = `fas ${option.icon}`;
        icon.style.marginRight = '5px';

        button.appendChild(icon);
        button.appendChild(document.createTextNode(option.text));

        button.addEventListener('click', () => handleOptionClick(option.value));
        optionsContainer.appendChild(button);
    });

    messageDiv.appendChild(optionsContainer);
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Add museum selection options
function addMuseumOptions() {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', 'bot-message');
    messageDiv.textContent = translations[state.language].museumSelect;

    const museumOptionsContainer = document.createElement('div');
    museumOptionsContainer.classList.add('museum-options');

    Object.keys(museums).forEach(key => {
        const museum = museums[key];
        const option = document.createElement('div');
        option.classList.add('museum-option');

        const img = document.createElement('img');
        img.src = museum.image;
        img.alt = museum.name;

        const title = document.createElement('div');
        title.classList.add('museum-option-title');
        title.textContent = museum.name;

        option.appendChild(img);
        option.appendChild(title);

        option.addEventListener('click', () => {
            state.museum = key;
            addUserMessage(`I want to learn about ${museum.name}`);

            showTypingIndicator();
            setTimeout(() => {
                removeTypingIndicator();
                addBotMessage(`${translations[state.language].museumChanged} ${museum.name}.`);

                setTimeout(() => {
                    showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator();
                        addBotMessageWithOptions(translations[state.language].options);
                    }, 700);
                }, 500);
            }, 700);
        });

        museumOptionsContainer.appendChild(option);
    });

    messageDiv.appendChild(museumOptionsContainer);
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Add user message to chat
function addUserMessage(text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', 'user-message');
    messageDiv.textContent = text;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Handle option button click
function handleOptionClick(option) {
    switch (option) {
        case 'info':
            addUserMessage(translations[state.language].option1);
            showTypingIndicator();

            setTimeout(() => {
                removeTypingIndicator();
                addBotMessage(museums[state.museum].info[state.language]);

                setTimeout(() => {
                    showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator();
                        addBotMessageWithOptions(translations[state.language].askMore);
                    }, 700);
                }, 1000);
            }, 700);
            break;

        case 'booking':
            addUserMessage(translations[state.language].option2);
            showTypingIndicator();

            setTimeout(() => {
                removeTypingIndicator();
                startBookingProcess();
            }, 700);
            break;

        case 'hours':
            addUserMessage(translations[state.language].option3);
            showTypingIndicator();

            setTimeout(() => {
                removeTypingIndicator();
                addBotMessage(museums[state.museum].hours[state.language]);

                setTimeout(() => {
                    showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator();
                        addBotMessageWithOptions(translations[state.language].askMore);
                    }, 700);
                }, 1000);
            }, 700);
            break;

        case 'location':
            addUserMessage(translations[state.language].option4);
            showTypingIndicator();

            setTimeout(() => {
                removeTypingIndicator();
                addBotMessage(museums[state.museum].location[state.language]);

                setTimeout(() => {
                    showTypingIndicator();
                    setTimeout(() => {
                        removeTypingIndicator();
                        addBotMessageWithOptions(translations[state.language].askMore);
                    }, 700);
                }, 1000);
            }, 700);
            break;
    }
}

// Start booking process
function startBookingProcess() {
    state.context = 'booking';

    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', 'bot-message');
    messageDiv.textContent = "Please fill in the details to book your tickets:";

    const bookingForm = document.createElement('div');
    bookingForm.classList.add('booking-form');

    // Create form elements
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.placeholder = 'Full Name';
    nameInput.required = true;

    const emailInput = document.createElement('input');
    emailInput.type = 'email';
    emailInput.placeholder = 'Email Address';
    emailInput.required = true;

    const phoneInput = document.createElement('input');
    phoneInput.type = 'tel';
    phoneInput.placeholder = 'Phone Number';
    phoneInput.required = true;

    const dateInput = document.createElement('input');
    dateInput.type = 'date';
    dateInput.required = true;

    // Restrict date selection to today and future dates
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    dateInput.value = today;

    const museumSelect = document.createElement('select');
    museumSelect.required = true;

    let ticketAvailability = {}; // Store ticket availability for validation

    // Box to display available tickets
    const availabilityBox = document.createElement('div');
    availabilityBox.classList.add('availability-box');
    const availabilityText = document.createElement('p');
    availabilityText.textContent = "Select a museum to see availability.";
    availabilityBox.appendChild(availabilityText);

    // Function to fetch and update ticket availability
    function updateTicketAvailability(selectedDate) {
        fetch(`fetch_availability.php?visit_date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                ticketAvailability = data; // Update availability data
                museumSelect.innerHTML = ''; // Clear existing options

                Object.keys(museums).forEach(key => {
                    const option = document.createElement('option');
                    const museumName = museums[key].name;
                    const availableTickets = data[museumName] || 0;

                    option.value = museumName;
                    option.textContent = museumName;
                    option.disabled = availableTickets === 0; // Disable if no tickets are available
                    museumSelect.appendChild(option);
                });

                // Pre-select the current museum if available
                if (museums[state.museum].name in data) {
                    museumSelect.value = museums[state.museum].name;
                }

                // Update availability box
                const selectedMuseum = museumSelect.value;
                const availableTickets = data[selectedMuseum] || 0;
                if (availableTickets > 0) {
                    availabilityText.textContent = `${availableTickets} tickets available for ${selectedMuseum} on ${selectedDate}.`;
                    availabilityBox.style.borderColor = '#28a745'; // Green border for availability
                } else {
                    availabilityText.textContent = `No tickets available for ${selectedMuseum} on ${selectedDate}.`;
                    availabilityBox.style.borderColor = '#dc3545'; // Red border for no availability
                }
            })
            .catch(error => {
                console.error('Error fetching ticket availability:', error);
                availabilityText.textContent = 'Error fetching ticket availability. Please try again.';
                availabilityBox.style.borderColor = '#ffc107'; // Yellow border for error
            });
    }

    // Fetch initial ticket availability for today's date
    updateTicketAvailability(dateInput.value);

    // Update ticket availability when the date changes
    dateInput.addEventListener('change', () => {
        updateTicketAvailability(dateInput.value);
    });

    // Update availability box when the museum changes
    museumSelect.addEventListener('change', () => {
        const selectedMuseum = museumSelect.value;
        const selectedDate = dateInput.value;
        const availableTickets = ticketAvailability[selectedMuseum] || 0;

        if (availableTickets > 0) {
            availabilityText.textContent = `${availableTickets} tickets available for ${selectedMuseum} on ${selectedDate}.`;
            availabilityBox.style.borderColor = '#28a745'; // Green border for availability
        } else {
            availabilityText.textContent = `No tickets available for ${selectedMuseum} on ${selectedDate}.`;
            availabilityBox.style.borderColor = '#dc3545'; // Red border for no availability
        }
    });

    const adultTicketsInput = document.createElement('input');
    adultTicketsInput.type = 'number';
    adultTicketsInput.placeholder = 'Adult Tickets'; // Updated to show placeholder
    adultTicketsInput.min = 0;

    const childTicketsInput = document.createElement('input');
    childTicketsInput.type = 'number';
    childTicketsInput.placeholder = 'Child Tickets'; // Updated to show placeholder
    childTicketsInput.min = 0;

    const submitButton = document.createElement('button');
    submitButton.textContent = 'Proceed to Payment';

    // Add form elements to the form
    bookingForm.appendChild(nameInput);
    bookingForm.appendChild(emailInput);
    bookingForm.appendChild(phoneInput);
    bookingForm.appendChild(dateInput);
    bookingForm.appendChild(museumSelect);
    bookingForm.appendChild(availabilityBox); // Add availability box below the museum selection
    bookingForm.appendChild(adultTicketsInput);
    bookingForm.appendChild(childTicketsInput);
    bookingForm.appendChild(submitButton);

    // Validate user inputs
    function validateInputs(name, email, phone) {
        // Name validation
        const nameRegex = /^[a-zA-Z\s]+$/;
        if (!nameRegex.test(name)) {
            alert("Name must contain only letters and spaces!");
            return false;
        }

        // Email validation
        const emailRegex = /^[a-z]+(?:\.[a-z]+)*[a-z0-9]*@[a-z0-9]+\.[a-z]{2,}$/;
        if (!emailRegex.test(email)) {
            alert("Invalid email format! Email must start with lowercase letters,Email cannot start with a dot! Dots are only allowed in the middle of letters and dots cannot be consecutive, and numbers are allowed only after letters.");
            return false;
        }

        if (!email.endsWith('@gmail.com')) {
            alert("Email must end with @gmail.com!");
            return false;
        }

        if (email.length < 8 || email.length > 30) {
            alert("Email must be between 8 to 40 characters!");
            return false;
        }

        // Phone validation
        const phoneRegex = /^[6-9]\d{9}$/;
        if (!phoneRegex.test(phone)) {
            alert("Phone number must be exactly 10 digits and start with 6, 7, 8, or 9!");
            return false;
        }

        // Ensure not all digits are the same
        if (/^(\d)\1{9}$/.test(phone)) {
            alert("Phone number cannot have all digits the same!");
            return false;
        }

        // Ensure no more than three consecutive identical digits
        if (/(\d)\1{3,}/.test(phone)) {
            alert("Phone number cannot have more than three consecutive identical digits!");
            return false;
        }

        return true;
    }

    // Handle form submission
    submitButton.addEventListener('click', () => {
        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const phone = phoneInput.value.trim();
        const date = dateInput.value;
        const museum = museumSelect.value;
        const adultTickets = parseInt(adultTicketsInput.value || 0); // Default to 0 if empty
        const childTickets = parseInt(childTicketsInput.value || 0); // Default to 0 if empty
        const totalTickets = adultTickets + childTickets;
        const totalAmount = (adultTickets * 120) + (childTickets * 60);

        // Validate inputs
        if (!validateInputs(name, email, phone)) {
            return;
        }

        if (!name || !email || !phone || !date || !museum) {
            alert('Please fill in all required fields.');
            return;
        }

        if (totalTickets <= 0) {
            alert('Please select at least one ticket (adult or child).');
            return;
        }

        // Validate ticket availability
        const availableTickets = ticketAvailability[museum] || 0;
        if (totalTickets > availableTickets) {
            alert(`Sorry, only ${availableTickets} tickets are available for ${museum} on ${date}. Please adjust your selection.`);
            return;
        }

        // Proceed with payment
        const formData = {
            billing_name: name,
            billing_email: email,
            billing_mobile: phone,
            shipping_name: name,
            shipping_email: email,
            shipping_mobile: phone,
            paymentOption: 'museum_ticket',
            payAmount: totalAmount,
            action: 'payOrder'
        };

        console.log('Sending data to submitpayment.php:', formData); // Debugging log

        fetch('submitpayment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            console.log('Response from submitpayment.php:', data); // Debugging log

            if (data.res === 'success') {
                const options = {
                    key: data.razorpay_key,
                    amount: data.userData.amount * 100, // Convert to paise
                    currency: 'INR',
                    name: 'Museum Ticket Booking',
                    description: data.userData.description,
                    order_id: data.userData.rpay_order_id,
                    handler: function (response) {
                        const urlParams = new URLSearchParams({
                            oid: data.order_number,
                            rp_payment_id: response.razorpay_payment_id,
                            rp_signature: response.razorpay_signature,
                            email: data.userData.email,
                            name: data.userData.name,
                            phone: data.userData.mobile,
                            visit_date: date,
                            museum: museum,
                            adults: adultTickets,
                            children: childTickets,
                            total: totalAmount
                        });
                        window.location.href = `payment-successbot.php?${urlParams.toString()}`;
                    },
                    prefill: {
                        name: data.userData.name,
                        email: data.userData.email,
                        contact: data.userData.mobile
                    },
                    theme: {
                        color: '#4361ee'
                    }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            } else {
                console.error('Payment initialization failed:', data.info); // Debugging log
                alert(data.info || 'Payment initialization failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error while initializing payment:', error); // Debugging log
            alert('An error occurred while initializing payment.');
        });
    });

    messageDiv.appendChild(bookingForm);
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

languageSelector.addEventListener('change', () => {
    state.language = languageSelector.value;

    showTypingIndicator();
    setTimeout(() => {
        removeTypingIndicator();
        addBotMessage(translations[state.language].languageChanged);

        setTimeout(() => {
            showTypingIndicator();
            setTimeout(() => {
                removeTypingIndicator();
                addBotMessageWithOptions(translations[state.language].options);
            }, 700);
        }, 500);
    }, 700);
});

museumSelector.addEventListener('change', () => {
    state.museum = museumSelector.value;

    // Update the booking form's museum selection if it exists
    const museumSelect = document.querySelector('.booking-form select');
    if (museumSelect) {
        const selectedMuseumName = museums[state.museum].name;
        museumSelect.value = selectedMuseumName; // Update the value directly
    }

    showTypingIndicator();
    setTimeout(() => {
        removeTypingIndicator();
        addBotMessage(`${translations[state.language].museumChanged} ${museums[state.museum].name}.`);

        setTimeout(() => {
            showTypingIndicator();
            setTimeout(() => {
                removeTypingIndicator();
                addBotMessageWithOptions(translations[state.language].options);
            }, 700);
        }, 500);
    }, 700);
});

// Check for booking_id in the URL and show the download ticket option
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const bookingId = urlParams.get('booking_id');

    if (bookingId) {
        showDownloadTicketOption(bookingId);
    }
});

// Show "Download Ticket" option
function showDownloadTicketOption(bookingId) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', 'bot-message');
    messageDiv.textContent = 'Your booking is confirmed! You can download your ticket below:';

    const downloadButton = document.createElement('button');
    downloadButton.textContent = 'Download Ticket';
    downloadButton.classList.add('download-ticket-button');
    downloadButton.addEventListener('click', () => {
        window.open(`download-ticket.php?id=${bookingId}`, '_blank');
    });

    messageDiv.appendChild(downloadButton);
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Show main options after the download ticket button
    setTimeout(() => {
        addBotMessageWithOptions(translations[state.language].options);
    }, 1000); // Add a slight delay for better user experience
}
