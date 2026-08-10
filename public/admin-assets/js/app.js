/* ==========================================
   HARITA MUSIC ACADEMY - CORE JAVASCRIPT
   ========================================== */

// Seed Data definition
const DEFAULT_STUDENTS = [
  {
    "id": "STU001",
    "name": "Ananya Iyer",
    "email": "ananya.iyer@gmail.com",
    "phone": "+91 98765 43210",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 8,
    "status": "Active",
    "enrolledDate": "2026-01-10",
    "enrolledAs": "Group",
    "groupId": "GRP001",
    "youtube": "https://www.youtube.com/watch?v=Vl3G_7hR9qQ"
  },
  {
    "id": "STU002",
    "name": "Rohan Malhotra",
    "email": "rohan.m@gmail.com",
    "phone": "+91 98123 45678",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 12,
    "status": "Active",
    "enrolledDate": "2026-02-15",
    "enrolledAs": "Group",
    "groupId": "GRP002"
  },
  {
    "id": "STU003",
    "name": "Sarah Fernandez",
    "email": "sarah.f@yahoo.com",
    "phone": "+91 99456 78901",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 3,
    "status": "Active",
    "enrolledDate": "2026-03-01",
    "enrolledAs": "Group",
    "groupId": "GRP001"
  },
  {
    "id": "STU004",
    "name": "Kabir Mehta",
    "email": "kabir.mehta@outlook.com",
    "phone": "+91 97654 32109",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 0,
    "status": "Pending Payment",
    "enrolledDate": "2026-05-12",
    "enrolledAs": "Individual",
    "groupId": ""
  },
  {
    "id": "STU005",
    "name": "Aria Sharma",
    "email": "aria.s@gmail.com",
    "phone": "+91 96543 21098",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 16,
    "status": "Active",
    "enrolledDate": "2026-04-20"
  },
  {
    "id": "STU006",
    "name": "Rahul Sen",
    "email": "rahul.sen@gmail.com",
    "phone": "+91 98765 0006",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 18,
    "status": "Inactive",
    "enrolledDate": "2026-01-07"
  },
  {
    "id": "STU007",
    "name": "Vikram Seth",
    "email": "vikram.seth@gmail.com",
    "phone": "+91 98765 0007",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 1,
    "status": "Active",
    "enrolledDate": "2026-01-08"
  },
  {
    "id": "STU008",
    "name": "Priya Nair",
    "email": "priya.nair@gmail.com",
    "phone": "+91 98765 0008",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 4,
    "status": "Active",
    "enrolledDate": "2026-01-09"
  },
  {
    "id": "STU009",
    "name": "Sneha Reddy",
    "email": "sneha.reddy@gmail.com",
    "phone": "+91 98765 0009",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 7,
    "status": "Active",
    "enrolledDate": "2026-01-10"
  },
  {
    "id": "STU010",
    "name": "Amit Patel",
    "email": "amit.patel@gmail.com",
    "phone": "+91 98765 0010",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 10,
    "status": "Active",
    "enrolledDate": "2026-01-11"
  },
  {
    "id": "STU011",
    "name": "Sonia Gandhi",
    "email": "sonia.gandhi@gmail.com",
    "phone": "+91 98765 0011",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 13,
    "status": "Active",
    "enrolledDate": "2026-01-12"
  },
  {
    "id": "STU012",
    "name": "Suresh Raina",
    "email": "suresh.raina@gmail.com",
    "phone": "+91 98765 0012",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 16,
    "status": "Inactive",
    "enrolledDate": "2026-01-13"
  },
  {
    "id": "STU013",
    "name": "Devendra Bhat",
    "email": "devendra.bhat@gmail.com",
    "phone": "+91 98765 0013",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 19,
    "status": "Active",
    "enrolledDate": "2026-01-14"
  },
  {
    "id": "STU014",
    "name": "Sandeep Varma",
    "email": "sandeep.varma@gmail.com",
    "phone": "+91 98765 0014",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 2,
    "status": "Active",
    "enrolledDate": "2026-01-15"
  },
  {
    "id": "STU015",
    "name": "Karan Johar",
    "email": "karan.johar@gmail.com",
    "phone": "+91 98765 0015",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 5,
    "status": "Active",
    "enrolledDate": "2026-01-16"
  },
  {
    "id": "STU016",
    "name": "Aditya Birla",
    "email": "aditya.birla@gmail.com",
    "phone": "+91 98765 0016",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 8,
    "status": "Active",
    "enrolledDate": "2026-01-17"
  },
  {
    "id": "STU017",
    "name": "Meera Nair",
    "email": "meera.nair@gmail.com",
    "phone": "+91 98765 0017",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 11,
    "status": "Active",
    "enrolledDate": "2026-01-18"
  },
  {
    "id": "STU018",
    "name": "Arjun Kapoor",
    "email": "arjun.kapoor@gmail.com",
    "phone": "+91 98765 0018",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 14,
    "status": "Inactive",
    "enrolledDate": "2026-01-19"
  },
  {
    "id": "STU019",
    "name": "Vijay Mallya",
    "email": "vijay.mallya@gmail.com",
    "phone": "+91 98765 0019",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 17,
    "status": "Active",
    "enrolledDate": "2026-01-20"
  },
  {
    "id": "STU020",
    "name": "Ratan Tata",
    "email": "ratan.tata@gmail.com",
    "phone": "+91 98765 0020",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 0,
    "status": "Active",
    "enrolledDate": "2026-01-21"
  },
  {
    "id": "STU021",
    "name": "Nisha Patel",
    "email": "nisha.patel@gmail.com",
    "phone": "+91 98765 0021",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 3,
    "status": "Active",
    "enrolledDate": "2026-01-22"
  },
  {
    "id": "STU022",
    "name": "Kirti Sen",
    "email": "kirti.sen@gmail.com",
    "phone": "+91 98765 0022",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 6,
    "status": "Active",
    "enrolledDate": "2026-01-23"
  },
  {
    "id": "STU023",
    "name": "Divya Menon",
    "email": "divya.menon@gmail.com",
    "phone": "+91 98765 0023",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 9,
    "status": "Active",
    "enrolledDate": "2026-01-24"
  },
  {
    "id": "STU024",
    "name": "Aishwarya Rai",
    "email": "aishwarya.rai@gmail.com",
    "phone": "+91 98765 0024",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 12,
    "status": "Inactive",
    "enrolledDate": "2026-01-25"
  },
  {
    "id": "STU025",
    "name": "Salman Khan",
    "email": "salman.khan@gmail.com",
    "phone": "+91 98765 0025",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 15,
    "status": "Active",
    "enrolledDate": "2026-01-26"
  },
  {
    "id": "STU026",
    "name": "Shah Rukh",
    "email": "shah.rukh@gmail.com",
    "phone": "+91 98765 0026",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 18,
    "status": "Active",
    "enrolledDate": "2026-01-27"
  },
  {
    "id": "STU027",
    "name": "Aamir Khan",
    "email": "aamir.khan@gmail.com",
    "phone": "+91 98765 0027",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 1,
    "status": "Active",
    "enrolledDate": "2026-01-28"
  },
  {
    "id": "STU028",
    "name": "Hrithik Roshan",
    "email": "hrithik.roshan@gmail.com",
    "phone": "+91 98765 0028",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 4,
    "status": "Active",
    "enrolledDate": "2026-01-01"
  },
  {
    "id": "STU029",
    "name": "Ranbir Kapoor",
    "email": "ranbir.kapoor@gmail.com",
    "phone": "+91 98765 0029",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 7,
    "status": "Active",
    "enrolledDate": "2026-01-02"
  },
  {
    "id": "STU030",
    "name": "Alia Bhatt",
    "email": "alia.bhatt@gmail.com",
    "phone": "+91 98765 0030",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 10,
    "status": "Inactive",
    "enrolledDate": "2026-01-03"
  },
  {
    "id": "STU031",
    "name": "Deepika Padukone",
    "email": "deepika.padukone@gmail.com",
    "phone": "+91 98765 0031",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 13,
    "status": "Active",
    "enrolledDate": "2026-01-04"
  },
  {
    "id": "STU032",
    "name": "Priyanka Chopra",
    "email": "priyanka.chopra@gmail.com",
    "phone": "+91 98765 0032",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 16,
    "status": "Active",
    "enrolledDate": "2026-01-05"
  },
  {
    "id": "STU033",
    "name": "Katrina Kaif",
    "email": "katrina.kaif@gmail.com",
    "phone": "+91 98765 0033",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 19,
    "status": "Active",
    "enrolledDate": "2026-01-06"
  },
  {
    "id": "STU034",
    "name": "Kareena Kapoor",
    "email": "kareena.kapoor@gmail.com",
    "phone": "+91 98765 0034",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 2,
    "status": "Active",
    "enrolledDate": "2026-01-07"
  },
  {
    "id": "STU035",
    "name": "Saif Ali",
    "email": "saif.ali@gmail.com",
    "phone": "+91 98765 0035",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 5,
    "status": "Active",
    "enrolledDate": "2026-01-08"
  },
  {
    "id": "STU036",
    "name": "Akshay Kumar",
    "email": "akshay.kumar@gmail.com",
    "phone": "+91 98765 0036",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 8,
    "status": "Inactive",
    "enrolledDate": "2026-01-09"
  },
  {
    "id": "STU037",
    "name": "Ajay Devgn",
    "email": "ajay.devgn@gmail.com",
    "phone": "+91 98765 0037",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 11,
    "status": "Active",
    "enrolledDate": "2026-01-10"
  },
  {
    "id": "STU038",
    "name": "Sunil Shetty",
    "email": "sunil.shetty@gmail.com",
    "phone": "+91 98765 0038",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 14,
    "status": "Active",
    "enrolledDate": "2026-01-11"
  },
  {
    "id": "STU039",
    "name": "Sanjay Dutt",
    "email": "sanjay.dutt@gmail.com",
    "phone": "+91 98765 0039",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 17,
    "status": "Active",
    "enrolledDate": "2026-01-12"
  },
  {
    "id": "STU040",
    "name": "Anil Kapoor",
    "email": "anil.kapoor@gmail.com",
    "phone": "+91 98765 0040",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 0,
    "status": "Active",
    "enrolledDate": "2026-01-13"
  },
  {
    "id": "STU041",
    "name": "Madhavan",
    "email": "madhavan@gmail.com",
    "phone": "+91 98765 0041",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 3,
    "status": "Active",
    "enrolledDate": "2026-01-14"
  },
  {
    "id": "STU042",
    "name": "Surya Kumar",
    "email": "surya.kumar@gmail.com",
    "phone": "+91 98765 0042",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 6,
    "status": "Inactive",
    "enrolledDate": "2026-01-15"
  },
  {
    "id": "STU043",
    "name": "Rohit Sharma",
    "email": "rohit.sharma@gmail.com",
    "phone": "+91 98765 0043",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 9,
    "status": "Active",
    "enrolledDate": "2026-01-16"
  },
  {
    "id": "STU044",
    "name": "Virat Kohli",
    "email": "virat.kohli@gmail.com",
    "phone": "+91 98765 0044",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 12,
    "status": "Active",
    "enrolledDate": "2026-01-17"
  },
  {
    "id": "STU045",
    "name": "MS Dhoni",
    "email": "ms.dhoni@gmail.com",
    "phone": "+91 98765 0045",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 15,
    "status": "Active",
    "enrolledDate": "2026-01-18"
  },
  {
    "id": "STU046",
    "name": "Sachin Tendulkar",
    "email": "sachin.tendulkar@gmail.com",
    "phone": "+91 98765 0046",
    "instrument": "Sitar",
    "teacher": "Pandit Ravi Sen",
    "credits": 18,
    "status": "Active",
    "enrolledDate": "2026-01-19"
  },
  {
    "id": "STU047",
    "name": "Sourav Ganguly",
    "email": "sourav.ganguly@gmail.com",
    "phone": "+91 98765 0047",
    "instrument": "Violin",
    "teacher": "Anjali Menon",
    "credits": 1,
    "status": "Active",
    "enrolledDate": "2026-01-20"
  },
  {
    "id": "STU048",
    "name": "Rahul Dravid",
    "email": "rahul.dravid@gmail.com",
    "phone": "+91 98765 0048",
    "instrument": "Flute",
    "teacher": "Hari Prasad Jr",
    "credits": 4,
    "status": "Inactive",
    "enrolledDate": "2026-01-21"
  },
  {
    "id": "STU049",
    "name": "VVS Laxman",
    "email": "vvs.laxman@gmail.com",
    "phone": "+91 98765 0049",
    "instrument": "Tabla",
    "teacher": "Zakir Hussain Fan",
    "credits": 7,
    "status": "Active",
    "enrolledDate": "2026-01-22"
  },
  {
    "id": "STU050",
    "name": "Kapil Dev",
    "email": "kapil.dev@gmail.com",
    "phone": "+91 98765 0050",
    "instrument": "Vocal (Carnatic)",
    "teacher": "Meera Sharma",
    "credits": 10,
    "status": "Active",
    "enrolledDate": "2026-01-23"
  }
];

const DEFAULT_TEACHERS = [
  {
    "id": "TCH001",
    "name": "Meera Sharma",
    "email": "meera.sharma@haritamusic.com",
    "phone": "+91 87654 32109",
    "instruments": [
      "Vocal",
      "Violin"
    ],
    "status": "Active",
    "bio": "Classical vocalist with 12+ years of teaching experience. Master of Hindustani and Carnatic vocals.",
    "weekOff": ["Sun"],
    "certifications": "Carnatic Vocal Acharya, Academy Representative",
    "youtube": "https://www.youtube.com/watch?v=z4s2_aHn8v0"
  },
  {
    "id": "TCH002",
    "name": "Pandit Ravi Sen",
    "email": "ravi.sen@haritamusic.com",
    "phone": "+91 88765 43210",
    "instruments": [
      "Sitar"
    ],
    "status": "Active",
    "bio": "Renowned sitar maestro. Disciple of global classical legends, focusing on complex ragas.",
    "weekOff": ["Mon"],
    "certifications": "Sitar Maestro Certification",
    "youtube": "https://www.youtube.com/watch?v=9xB_X9BOAOU"
  },
  {
    "id": "TCH003",
    "name": "Anjali Menon",
    "email": "anjali.menon@haritamusic.com",
    "phone": "+91 89765 43211",
    "instruments": [
      "Violin",
      "Keyboard"
    ],
    "status": "Active",
    "bio": "Dual instrumentalist passionate about fusion classical and Western notation styles.",
    "weekOff": ["Sat", "Sun"],
    "certifications": "Violin Visharad, Trinity Grade 8",
    "youtube": "https://www.youtube.com/watch?v=jW8mG2cT240"
  },
  {
    "id": "TCH004",
    "name": "Hari Prasad Jr",
    "email": "hari.prasad@haritamusic.com",
    "phone": "+91 80765 43212",
    "instruments": [
      "Flute"
    ],
    "status": "Active",
    "bio": "Flautist dedicated to capturing the soothing patterns of woodwind classical instruments."
  },
  {
    "id": "TCH005",
    "name": "Zakir Hussain Fan",
    "email": "zakir.hussain.fan@haritamusic.com",
    "phone": "+91 80765 0005",
    "instruments": [
      "Vocal (Carnatic)"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Vocal (Carnatic) techniques for advanced classes."
  },
  {
    "id": "TCH006",
    "name": "Amjad Ali",
    "email": "amjad.ali@haritamusic.com",
    "phone": "+91 80765 0006",
    "instruments": [
      "Sitar"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Sitar techniques for advanced classes."
  },
  {
    "id": "TCH007",
    "name": "Shiv Kumar",
    "email": "shiv.kumar@haritamusic.com",
    "phone": "+91 80765 0007",
    "instruments": [
      "Violin"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Violin techniques for advanced classes."
  },
  {
    "id": "TCH008",
    "name": "Hariprasad Chaurasia Jr",
    "email": "hariprasad.chaurasia.jr@haritamusic.com",
    "phone": "+91 80765 0008",
    "instruments": [
      "Flute"
    ],
    "status": "Inactive",
    "bio": "Passionate classical instructor focusing on Flute techniques for advanced classes."
  },
  {
    "id": "TCH009",
    "name": "L. Subramaniam",
    "email": "l..subramaniam@haritamusic.com",
    "phone": "+91 80765 0009",
    "instruments": [
      "Tabla"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Tabla techniques for advanced classes."
  },
  {
    "id": "TCH010",
    "name": "Anoushka Shankar",
    "email": "anoushka.shankar@haritamusic.com",
    "phone": "+91 80765 0010",
    "instruments": [
      "Vocal (Carnatic)"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Vocal (Carnatic) techniques for advanced classes."
  },
  {
    "id": "TCH011",
    "name": "Bismillah Khan Fan",
    "email": "bismillah.khan.fan@haritamusic.com",
    "phone": "+91 80765 0011",
    "instruments": [
      "Sitar"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Sitar techniques for advanced classes."
  },
  {
    "id": "TCH012",
    "name": "Alla Rakha",
    "email": "alla.rakha@haritamusic.com",
    "phone": "+91 80765 0012",
    "instruments": [
      "Violin"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Violin techniques for advanced classes."
  },
  {
    "id": "TCH013",
    "name": "Sultan Khan",
    "email": "sultan.khan@haritamusic.com",
    "phone": "+91 80765 0013",
    "instruments": [
      "Flute"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Flute techniques for advanced classes."
  },
  {
    "id": "TCH014",
    "name": "Vishwa Mohan",
    "email": "vishwa.mohan@haritamusic.com",
    "phone": "+91 80765 0014",
    "instruments": [
      "Tabla"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Tabla techniques for advanced classes."
  },
  {
    "id": "TCH015",
    "name": "T.N. Krishnan",
    "email": "t.n..krishnan@haritamusic.com",
    "phone": "+91 80765 0015",
    "instruments": [
      "Vocal (Carnatic)"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Vocal (Carnatic) techniques for advanced classes."
  },
  {
    "id": "TCH016",
    "name": "Lalgudi Jayaraman",
    "email": "lalgudi.jayaraman@haritamusic.com",
    "phone": "+91 80765 0016",
    "instruments": [
      "Sitar"
    ],
    "status": "Inactive",
    "bio": "Passionate classical instructor focusing on Sitar techniques for advanced classes."
  },
  {
    "id": "TCH017",
    "name": "U. Srinivas",
    "email": "u..srinivas@haritamusic.com",
    "phone": "+91 80765 0017",
    "instruments": [
      "Violin"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Violin techniques for advanced classes."
  },
  {
    "id": "TCH018",
    "name": "K.S. Chithra",
    "email": "k.s..chithra@haritamusic.com",
    "phone": "+91 80765 0018",
    "instruments": [
      "Flute"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Flute techniques for advanced classes."
  },
  {
    "id": "TCH019",
    "name": "S.P. Balasubrahmanyam",
    "email": "s.p..balasubrahmanyam@haritamusic.com",
    "phone": "+91 80765 0019",
    "instruments": [
      "Tabla"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Tabla techniques for advanced classes."
  },
  {
    "id": "TCH020",
    "name": "Hariharan",
    "email": "hariharan@haritamusic.com",
    "phone": "+91 80765 0020",
    "instruments": [
      "Vocal (Carnatic)"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Vocal (Carnatic) techniques for advanced classes."
  },
  {
    "id": "TCH021",
    "name": "Yesudas",
    "email": "yesudas@haritamusic.com",
    "phone": "+91 80765 0021",
    "instruments": [
      "Sitar"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Sitar techniques for advanced classes."
  },
  {
    "id": "TCH022",
    "name": "Shreya Ghoshal",
    "email": "shreya.ghoshal@haritamusic.com",
    "phone": "+91 80765 0022",
    "instruments": [
      "Violin"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Violin techniques for advanced classes."
  },
  {
    "id": "TCH023",
    "name": "Arijit Singh",
    "email": "arijit.singh@haritamusic.com",
    "phone": "+91 80765 0023",
    "instruments": [
      "Flute"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Flute techniques for advanced classes."
  },
  {
    "id": "TCH024",
    "name": "Sonu Nigam",
    "email": "sonu.nigam@haritamusic.com",
    "phone": "+91 80765 0024",
    "instruments": [
      "Tabla"
    ],
    "status": "Inactive",
    "bio": "Passionate classical instructor focusing on Tabla techniques for advanced classes."
  },
  {
    "id": "TCH025",
    "name": "Shankar Mahadevan",
    "email": "shankar.mahadevan@haritamusic.com",
    "phone": "+91 80765 0025",
    "instruments": [
      "Vocal (Carnatic)"
    ],
    "status": "Active",
    "bio": "Passionate classical instructor focusing on Vocal (Carnatic) techniques for advanced classes."
  }
];

const DEFAULT_CLASSES = [
  {
    "id": "CLS001",
    "studentName": "Rohan Malhotra",
    "teacherName": "Pandit Ravi Sen",
    "instrument": "Sitar",
    "dateTime": "2026-07-11T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS002",
    "studentName": "Sarah Fernandez",
    "teacherName": "Anjali Menon",
    "instrument": "Violin",
    "dateTime": "2026-07-12T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS003",
    "studentName": "Kabir Mehta",
    "teacherName": "Hari Prasad Jr",
    "instrument": "Flute",
    "dateTime": "2026-07-13T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS004",
    "studentName": "Aria Sharma",
    "teacherName": "Zakir Hussain Fan",
    "instrument": "Tabla",
    "dateTime": "2026-07-14T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS005",
    "studentName": "Rahul Sen",
    "teacherName": "Amjad Ali",
    "instrument": "Sitar",
    "dateTime": "2026-07-15T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS006",
    "studentName": "Vikram Seth",
    "teacherName": "Shiv Kumar",
    "instrument": "Violin",
    "dateTime": "2026-07-16T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS007",
    "studentName": "Priya Nair",
    "teacherName": "Hariprasad Chaurasia Jr",
    "instrument": "Flute",
    "dateTime": "2026-07-17T18:00",
    "duration": "60 mins",
    "status": "Reschedule Requested"
  },
  {
    "id": "CLS008",
    "studentName": "Sneha Reddy",
    "teacherName": "L. Subramaniam",
    "instrument": "Tabla",
    "dateTime": "2026-07-18T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS009",
    "studentName": "Amit Patel",
    "teacherName": "Anoushka Shankar",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-19T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS010",
    "studentName": "Sonia Gandhi",
    "teacherName": "Bismillah Khan Fan",
    "instrument": "Sitar",
    "dateTime": "2026-07-20T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS011",
    "studentName": "Suresh Raina",
    "teacherName": "Alla Rakha",
    "instrument": "Violin",
    "dateTime": "2026-07-21T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS012",
    "studentName": "Devendra Bhat",
    "teacherName": "Sultan Khan",
    "instrument": "Flute",
    "dateTime": "2026-07-22T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS013",
    "studentName": "Sandeep Varma",
    "teacherName": "Vishwa Mohan",
    "instrument": "Tabla",
    "dateTime": "2026-07-23T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS014",
    "studentName": "Karan Johar",
    "teacherName": "T.N. Krishnan",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-24T18:00",
    "duration": "60 mins",
    "status": "Reschedule Requested"
  },
  {
    "id": "CLS015",
    "studentName": "Aditya Birla",
    "teacherName": "Lalgudi Jayaraman",
    "instrument": "Sitar",
    "dateTime": "2026-07-25T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS016",
    "studentName": "Meera Nair",
    "teacherName": "U. Srinivas",
    "instrument": "Violin",
    "dateTime": "2026-07-26T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS017",
    "studentName": "Arjun Kapoor",
    "teacherName": "K.S. Chithra",
    "instrument": "Flute",
    "dateTime": "2026-07-27T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS018",
    "studentName": "Vijay Mallya",
    "teacherName": "S.P. Balasubrahmanyam",
    "instrument": "Tabla",
    "dateTime": "2026-07-28T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS019",
    "studentName": "Ratan Tata",
    "teacherName": "Hariharan",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-29T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS020",
    "studentName": "Nisha Patel",
    "teacherName": "Yesudas",
    "instrument": "Sitar",
    "dateTime": "2026-07-10T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS021",
    "studentName": "Kirti Sen",
    "teacherName": "Shreya Ghoshal",
    "instrument": "Violin",
    "dateTime": "2026-07-11T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS022",
    "studentName": "Divya Menon",
    "teacherName": "Arijit Singh",
    "instrument": "Flute",
    "dateTime": "2026-07-12T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS023",
    "studentName": "Aishwarya Rai",
    "teacherName": "Sonu Nigam",
    "instrument": "Tabla",
    "dateTime": "2026-07-13T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS024",
    "studentName": "Salman Khan",
    "teacherName": "Shankar Mahadevan",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-14T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS025",
    "studentName": "Shah Rukh",
    "teacherName": "Meera Sharma",
    "instrument": "Sitar",
    "dateTime": "2026-07-15T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS026",
    "studentName": "Aamir Khan",
    "teacherName": "Pandit Ravi Sen",
    "instrument": "Violin",
    "dateTime": "2026-07-16T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS027",
    "studentName": "Hrithik Roshan",
    "teacherName": "Anjali Menon",
    "instrument": "Flute",
    "dateTime": "2026-07-17T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS028",
    "studentName": "Ranbir Kapoor",
    "teacherName": "Hari Prasad Jr",
    "instrument": "Tabla",
    "dateTime": "2026-07-18T18:00",
    "duration": "60 mins",
    "status": "Reschedule Requested"
  },
  {
    "id": "CLS029",
    "studentName": "Alia Bhatt",
    "teacherName": "Zakir Hussain Fan",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-19T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS030",
    "studentName": "Deepika Padukone",
    "teacherName": "Amjad Ali",
    "instrument": "Sitar",
    "dateTime": "2026-07-20T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS031",
    "studentName": "Priyanka Chopra",
    "teacherName": "Shiv Kumar",
    "instrument": "Violin",
    "dateTime": "2026-07-21T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS032",
    "studentName": "Katrina Kaif",
    "teacherName": "Hariprasad Chaurasia Jr",
    "instrument": "Flute",
    "dateTime": "2026-07-22T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS033",
    "studentName": "Kareena Kapoor",
    "teacherName": "L. Subramaniam",
    "instrument": "Tabla",
    "dateTime": "2026-07-23T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS034",
    "studentName": "Saif Ali",
    "teacherName": "Anoushka Shankar",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-24T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS035",
    "studentName": "Akshay Kumar",
    "teacherName": "Bismillah Khan Fan",
    "instrument": "Sitar",
    "dateTime": "2026-07-25T18:00",
    "duration": "60 mins",
    "status": "Reschedule Requested"
  },
  {
    "id": "CLS036",
    "studentName": "Ajay Devgn",
    "teacherName": "Alla Rakha",
    "instrument": "Violin",
    "dateTime": "2026-07-26T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS037",
    "studentName": "Sunil Shetty",
    "teacherName": "Sultan Khan",
    "instrument": "Flute",
    "dateTime": "2026-07-27T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS038",
    "studentName": "Sanjay Dutt",
    "teacherName": "Vishwa Mohan",
    "instrument": "Tabla",
    "dateTime": "2026-07-28T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS039",
    "studentName": "Anil Kapoor",
    "teacherName": "T.N. Krishnan",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-29T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS040",
    "studentName": "Madhavan",
    "teacherName": "Lalgudi Jayaraman",
    "instrument": "Sitar",
    "dateTime": "2026-07-10T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS041",
    "studentName": "Surya Kumar",
    "teacherName": "U. Srinivas",
    "instrument": "Violin",
    "dateTime": "2026-07-11T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS042",
    "studentName": "Rohit Sharma",
    "teacherName": "K.S. Chithra",
    "instrument": "Flute",
    "dateTime": "2026-07-12T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS043",
    "studentName": "Virat Kohli",
    "teacherName": "S.P. Balasubrahmanyam",
    "instrument": "Tabla",
    "dateTime": "2026-07-13T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS044",
    "studentName": "MS Dhoni",
    "teacherName": "Hariharan",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-14T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS045",
    "studentName": "Sachin Tendulkar",
    "teacherName": "Yesudas",
    "instrument": "Sitar",
    "dateTime": "2026-07-15T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS046",
    "studentName": "Sourav Ganguly",
    "teacherName": "Shreya Ghoshal",
    "instrument": "Violin",
    "dateTime": "2026-07-16T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS047",
    "studentName": "Rahul Dravid",
    "teacherName": "Arijit Singh",
    "instrument": "Flute",
    "dateTime": "2026-07-17T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS048",
    "studentName": "VVS Laxman",
    "teacherName": "Sonu Nigam",
    "instrument": "Tabla",
    "dateTime": "2026-07-18T18:00",
    "duration": "60 mins",
    "status": "Completed"
  },
  {
    "id": "CLS049",
    "studentName": "Kapil Dev",
    "teacherName": "Shankar Mahadevan",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-19T18:00",
    "duration": "60 mins",
    "status": "Reschedule Requested"
  },
  {
    "id": "CLS050",
    "studentName": "Ananya Iyer",
    "teacherName": "Meera Sharma",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-20T18:00",
    "duration": "60 mins",
    "status": "Scheduled"
  },
  {
    "id": "CLS051",
    "studentName": "Rajesh Khanna",
    "teacherName": "Meera Sharma",
    "instrument": "Sitar",
    "dateTime": "2026-07-28T10:00",
    "duration": "45 mins",
    "status": "Scheduled",
    "isDemo": true
  },
  {
    "id": "CLS052",
    "studentName": "Kiran Bedi",
    "teacherName": "Meera Sharma",
    "instrument": "Vocal (Carnatic)",
    "dateTime": "2026-07-29T14:30",
    "duration": "60 mins",
    "status": "Completed",
    "isDemo": true
  },
  {
    "id": "CLS053",
    "studentName": "Vikram Ambalal",
    "teacherName": "Pandit Ravi Sen",
    "instrument": "Violin",
    "dateTime": "2026-07-30T16:00",
    "duration": "45 mins",
    "status": "Converted",
    "isDemo": true
  },
  {
    "id": "CLS054",
    "studentName": "Devi Prasad",
    "teacherName": "Meera Sharma",
    "instrument": "Tabla",
    "dateTime": "2026-07-31T11:00",
    "duration": "45 mins",
    "status": "No Show",
    "isDemo": true
  }
];

const DEFAULT_LEAVES = [
  {
    "id": "LEV001",
    "teacherName": "Pandit Ravi Sen",
    "startDate": "2026-08-02",
    "endDate": "2026-08-04",
    "reason": "Music Concert Tour",
    "status": "Pending",
    "coverTeacher": "Anjali Menon"
  },
  {
    "id": "LEV002",
    "teacherName": "Anjali Menon",
    "startDate": "2026-08-03",
    "endDate": "2026-08-05",
    "reason": "Medical Leave",
    "status": "Approved",
    "coverTeacher": "Hari Prasad Jr"
  },
  {
    "id": "LEV003",
    "teacherName": "Hari Prasad Jr",
    "startDate": "2026-08-04",
    "endDate": "2026-08-06",
    "reason": "Personal Work",
    "status": "Pending",
    "coverTeacher": "Zakir Hussain Fan"
  },
  {
    "id": "LEV004",
    "teacherName": "Zakir Hussain Fan",
    "startDate": "2026-08-05",
    "endDate": "2026-08-07",
    "reason": "Festival Celebration",
    "status": "Approved",
    "coverTeacher": "Amjad Ali"
  },
  {
    "id": "LEV005",
    "teacherName": "Amjad Ali",
    "startDate": "2026-08-06",
    "endDate": "2026-08-08",
    "reason": "Family Function",
    "status": "Declined",
    "coverTeacher": "Shiv Kumar"
  },
  {
    "id": "LEV006",
    "teacherName": "Shiv Kumar",
    "startDate": "2026-08-07",
    "endDate": "2026-08-09",
    "reason": "Music Concert Tour",
    "status": "Approved",
    "coverTeacher": "Hariprasad Chaurasia Jr"
  },
  {
    "id": "LEV007",
    "teacherName": "Hariprasad Chaurasia Jr",
    "startDate": "2026-08-08",
    "endDate": "2026-08-10",
    "reason": "Medical Leave",
    "status": "Pending",
    "coverTeacher": "L. Subramaniam"
  },
  {
    "id": "LEV008",
    "teacherName": "L. Subramaniam",
    "startDate": "2026-08-09",
    "endDate": "2026-08-11",
    "reason": "Personal Work",
    "status": "Approved",
    "coverTeacher": "Anoushka Shankar"
  },
  {
    "id": "LEV009",
    "teacherName": "Anoushka Shankar",
    "startDate": "2026-08-10",
    "endDate": "2026-08-12",
    "reason": "Festival Celebration",
    "status": "Pending",
    "coverTeacher": "Bismillah Khan Fan"
  },
  {
    "id": "LEV010",
    "teacherName": "Bismillah Khan Fan",
    "startDate": "2026-08-11",
    "endDate": "2026-08-13",
    "reason": "Family Function",
    "status": "Approved",
    "coverTeacher": "Alla Rakha"
  },
  {
    "id": "LEV011",
    "teacherName": "Alla Rakha",
    "startDate": "2026-08-12",
    "endDate": "2026-08-14",
    "reason": "Music Concert Tour",
    "status": "Pending",
    "coverTeacher": "Sultan Khan"
  },
  {
    "id": "LEV012",
    "teacherName": "Sultan Khan",
    "startDate": "2026-08-13",
    "endDate": "2026-08-15",
    "reason": "Medical Leave",
    "status": "Approved",
    "coverTeacher": "Vishwa Mohan"
  },
  {
    "id": "LEV013",
    "teacherName": "Vishwa Mohan",
    "startDate": "2026-08-14",
    "endDate": "2026-08-16",
    "reason": "Personal Work",
    "status": "Pending",
    "coverTeacher": "T.N. Krishnan"
  },
  {
    "id": "LEV014",
    "teacherName": "T.N. Krishnan",
    "startDate": "2026-08-15",
    "endDate": "2026-08-17",
    "reason": "Festival Celebration",
    "status": "Approved",
    "coverTeacher": "Lalgudi Jayaraman"
  },
  {
    "id": "LEV015",
    "teacherName": "Lalgudi Jayaraman",
    "startDate": "2026-08-01",
    "endDate": "2026-08-03",
    "reason": "Family Function",
    "status": "Declined",
    "coverTeacher": "U. Srinivas"
  },
  {
    "id": "LEV016",
    "teacherName": "U. Srinivas",
    "startDate": "2026-08-02",
    "endDate": "2026-08-04",
    "reason": "Music Concert Tour",
    "status": "Approved",
    "coverTeacher": "K.S. Chithra"
  },
  {
    "id": "LEV017",
    "teacherName": "K.S. Chithra",
    "startDate": "2026-08-03",
    "endDate": "2026-08-05",
    "reason": "Medical Leave",
    "status": "Pending",
    "coverTeacher": "S.P. Balasubrahmanyam"
  },
  {
    "id": "LEV018",
    "teacherName": "S.P. Balasubrahmanyam",
    "startDate": "2026-08-04",
    "endDate": "2026-08-06",
    "reason": "Personal Work",
    "status": "Approved",
    "coverTeacher": "Hariharan"
  },
  {
    "id": "LEV019",
    "teacherName": "Hariharan",
    "startDate": "2026-08-05",
    "endDate": "2026-08-07",
    "reason": "Festival Celebration",
    "status": "Pending",
    "coverTeacher": "Yesudas"
  },
  {
    "id": "LEV020",
    "teacherName": "Yesudas",
    "startDate": "2026-08-06",
    "endDate": "2026-08-08",
    "reason": "Family Function",
    "status": "Approved",
    "coverTeacher": "Shreya Ghoshal"
  },
  {
    "id": "LEV021",
    "teacherName": "Shreya Ghoshal",
    "startDate": "2026-08-07",
    "endDate": "2026-08-09",
    "reason": "Music Concert Tour",
    "status": "Pending",
    "coverTeacher": "Arijit Singh"
  },
  {
    "id": "LEV022",
    "teacherName": "Arijit Singh",
    "startDate": "2026-08-08",
    "endDate": "2026-08-10",
    "reason": "Medical Leave",
    "status": "Approved",
    "coverTeacher": "Sonu Nigam"
  },
  {
    "id": "LEV023",
    "teacherName": "Sonu Nigam",
    "startDate": "2026-08-09",
    "endDate": "2026-08-11",
    "reason": "Personal Work",
    "status": "Pending",
    "coverTeacher": "Shankar Mahadevan"
  },
  {
    "id": "LEV024",
    "teacherName": "Shankar Mahadevan",
    "startDate": "2026-08-10",
    "endDate": "2026-08-12",
    "reason": "Festival Celebration",
    "status": "Approved",
    "coverTeacher": "Meera Sharma"
  },
  {
    "id": "LEV025",
    "teacherName": "Meera Sharma",
    "startDate": "2026-08-11",
    "endDate": "2026-08-13",
    "reason": "Family Function",
    "status": "Declined",
    "coverTeacher": "Pandit Ravi Sen"
  },
  {
    "id": "LEV026",
    "teacherName": "Pandit Ravi Sen",
    "startDate": "2026-08-12",
    "endDate": "2026-08-14",
    "reason": "Music Concert Tour",
    "status": "Approved",
    "coverTeacher": "Anjali Menon"
  },
  {
    "id": "LEV027",
    "teacherName": "Anjali Menon",
    "startDate": "2026-08-13",
    "endDate": "2026-08-15",
    "reason": "Medical Leave",
    "status": "Pending",
    "coverTeacher": "Hari Prasad Jr"
  },
  {
    "id": "LEV028",
    "teacherName": "Hari Prasad Jr",
    "startDate": "2026-08-14",
    "endDate": "2026-08-16",
    "reason": "Personal Work",
    "status": "Approved",
    "coverTeacher": "Zakir Hussain Fan"
  },
  {
    "id": "LEV029",
    "teacherName": "Zakir Hussain Fan",
    "startDate": "2026-08-15",
    "endDate": "2026-08-17",
    "reason": "Festival Celebration",
    "status": "Pending",
    "coverTeacher": "Amjad Ali"
  },
  {
    "id": "LEV030",
    "teacherName": "Amjad Ali",
    "startDate": "2026-08-01",
    "endDate": "2026-08-03",
    "reason": "Family Function",
    "status": "Approved",
    "coverTeacher": "Shiv Kumar"
  },
  {
    "id": "LEV031",
    "teacherName": "Shiv Kumar",
    "startDate": "2026-08-02",
    "endDate": "2026-08-04",
    "reason": "Music Concert Tour",
    "status": "Pending",
    "coverTeacher": "Hariprasad Chaurasia Jr"
  },
  {
    "id": "LEV032",
    "teacherName": "Hariprasad Chaurasia Jr",
    "startDate": "2026-08-03",
    "endDate": "2026-08-05",
    "reason": "Medical Leave",
    "status": "Approved",
    "coverTeacher": "L. Subramaniam"
  },
  {
    "id": "LEV033",
    "teacherName": "L. Subramaniam",
    "startDate": "2026-08-04",
    "endDate": "2026-08-06",
    "reason": "Personal Work",
    "status": "Pending",
    "coverTeacher": "Anoushka Shankar"
  },
  {
    "id": "LEV034",
    "teacherName": "Anoushka Shankar",
    "startDate": "2026-08-05",
    "endDate": "2026-08-07",
    "reason": "Festival Celebration",
    "status": "Approved",
    "coverTeacher": "Bismillah Khan Fan"
  },
  {
    "id": "LEV035",
    "teacherName": "Bismillah Khan Fan",
    "startDate": "2026-08-06",
    "endDate": "2026-08-08",
    "reason": "Family Function",
    "status": "Declined",
    "coverTeacher": "Alla Rakha"
  },
  {
    "id": "LEV036",
    "teacherName": "Alla Rakha",
    "startDate": "2026-08-07",
    "endDate": "2026-08-09",
    "reason": "Music Concert Tour",
    "status": "Approved",
    "coverTeacher": "Sultan Khan"
  },
  {
    "id": "LEV037",
    "teacherName": "Sultan Khan",
    "startDate": "2026-08-08",
    "endDate": "2026-08-10",
    "reason": "Medical Leave",
    "status": "Pending",
    "coverTeacher": "Vishwa Mohan"
  },
  {
    "id": "LEV038",
    "teacherName": "Vishwa Mohan",
    "startDate": "2026-08-09",
    "endDate": "2026-08-11",
    "reason": "Personal Work",
    "status": "Approved",
    "coverTeacher": "T.N. Krishnan"
  },
  {
    "id": "LEV039",
    "teacherName": "T.N. Krishnan",
    "startDate": "2026-08-10",
    "endDate": "2026-08-12",
    "reason": "Festival Celebration",
    "status": "Pending",
    "coverTeacher": "Lalgudi Jayaraman"
  },
  {
    "id": "LEV040",
    "teacherName": "Lalgudi Jayaraman",
    "startDate": "2026-08-11",
    "endDate": "2026-08-13",
    "reason": "Family Function",
    "status": "Approved",
    "coverTeacher": "U. Srinivas"
  },
  {
    "id": "LEV041",
    "teacherName": "U. Srinivas",
    "startDate": "2026-08-12",
    "endDate": "2026-08-14",
    "reason": "Music Concert Tour",
    "status": "Pending",
    "coverTeacher": "K.S. Chithra"
  },
  {
    "id": "LEV042",
    "teacherName": "K.S. Chithra",
    "startDate": "2026-08-13",
    "endDate": "2026-08-15",
    "reason": "Medical Leave",
    "status": "Approved",
    "coverTeacher": "S.P. Balasubrahmanyam"
  },
  {
    "id": "LEV043",
    "teacherName": "S.P. Balasubrahmanyam",
    "startDate": "2026-08-14",
    "endDate": "2026-08-16",
    "reason": "Personal Work",
    "status": "Pending",
    "coverTeacher": "Hariharan"
  },
  {
    "id": "LEV044",
    "teacherName": "Hariharan",
    "startDate": "2026-08-15",
    "endDate": "2026-08-17",
    "reason": "Festival Celebration",
    "status": "Approved",
    "coverTeacher": "Yesudas"
  },
  {
    "id": "LEV045",
    "teacherName": "Yesudas",
    "startDate": "2026-08-01",
    "endDate": "2026-08-03",
    "reason": "Family Function",
    "status": "Declined",
    "coverTeacher": "Shreya Ghoshal"
  },
  {
    "id": "LEV046",
    "teacherName": "Shreya Ghoshal",
    "startDate": "2026-08-02",
    "endDate": "2026-08-04",
    "reason": "Music Concert Tour",
    "status": "Approved",
    "coverTeacher": "Arijit Singh"
  },
  {
    "id": "LEV047",
    "teacherName": "Arijit Singh",
    "startDate": "2026-08-03",
    "endDate": "2026-08-05",
    "reason": "Medical Leave",
    "status": "Pending",
    "coverTeacher": "Sonu Nigam"
  },
  {
    "id": "LEV048",
    "teacherName": "Sonu Nigam",
    "startDate": "2026-08-04",
    "endDate": "2026-08-06",
    "reason": "Personal Work",
    "status": "Approved",
    "coverTeacher": "Shankar Mahadevan"
  },
  {
    "id": "LEV049",
    "teacherName": "Shankar Mahadevan",
    "startDate": "2026-08-05",
    "endDate": "2026-08-07",
    "reason": "Festival Celebration",
    "status": "Pending",
    "coverTeacher": "Meera Sharma"
  },
  {
    "id": "LEV050",
    "teacherName": "Meera Sharma",
    "startDate": "2026-08-06",
    "endDate": "2026-08-08",
    "reason": "Family Function",
    "status": "Approved",
    "coverTeacher": "Pandit Ravi Sen"
  }
];

const DEFAULT_SALES = [
  {
    "id": "SAL001",
    "studentName": "Rohan Malhotra",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-02"
  },
  {
    "id": "SAL002",
    "studentName": "Sarah Fernandez",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-03"
  },
  {
    "id": "SAL003",
    "studentName": "Kabir Mehta",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-04"
  },
  {
    "id": "SAL004",
    "studentName": "Aria Sharma",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-05"
  },
  {
    "id": "SAL005",
    "studentName": "Rahul Sen",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-06"
  },
  {
    "id": "SAL006",
    "studentName": "Vikram Seth",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-07"
  },
  {
    "id": "SAL007",
    "studentName": "Priya Nair",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-08"
  },
  {
    "id": "SAL008",
    "studentName": "Sneha Reddy",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-09"
  },
  {
    "id": "SAL009",
    "studentName": "Amit Patel",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-10"
  },
  {
    "id": "SAL010",
    "studentName": "Sonia Gandhi",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-11"
  },
  {
    "id": "SAL011",
    "studentName": "Suresh Raina",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-12"
  },
  {
    "id": "SAL012",
    "studentName": "Devendra Bhat",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-13"
  },
  {
    "id": "SAL013",
    "studentName": "Sandeep Varma",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-14"
  },
  {
    "id": "SAL014",
    "studentName": "Karan Johar",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-15"
  },
  {
    "id": "SAL015",
    "studentName": "Aditya Birla",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-16"
  },
  {
    "id": "SAL016",
    "studentName": "Meera Nair",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-17"
  },
  {
    "id": "SAL017",
    "studentName": "Arjun Kapoor",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-18"
  },
  {
    "id": "SAL018",
    "studentName": "Vijay Mallya",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-19"
  },
  {
    "id": "SAL019",
    "studentName": "Ratan Tata",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-20"
  },
  {
    "id": "SAL020",
    "studentName": "Nisha Patel",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-21"
  },
  {
    "id": "SAL021",
    "studentName": "Kirti Sen",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-22"
  },
  {
    "id": "SAL022",
    "studentName": "Divya Menon",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-23"
  },
  {
    "id": "SAL023",
    "studentName": "Aishwarya Rai",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-01"
  },
  {
    "id": "SAL024",
    "studentName": "Salman Khan",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-02"
  },
  {
    "id": "SAL025",
    "studentName": "Shah Rukh",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-03"
  },
  {
    "id": "SAL026",
    "studentName": "Aamir Khan",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-04"
  },
  {
    "id": "SAL027",
    "studentName": "Hrithik Roshan",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-05"
  },
  {
    "id": "SAL028",
    "studentName": "Ranbir Kapoor",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-06"
  },
  {
    "id": "SAL029",
    "studentName": "Alia Bhatt",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-07"
  },
  {
    "id": "SAL030",
    "studentName": "Deepika Padukone",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-08"
  },
  {
    "id": "SAL031",
    "studentName": "Priyanka Chopra",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-09"
  },
  {
    "id": "SAL032",
    "studentName": "Katrina Kaif",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-10"
  },
  {
    "id": "SAL033",
    "studentName": "Kareena Kapoor",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-11"
  },
  {
    "id": "SAL034",
    "studentName": "Saif Ali",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-12"
  },
  {
    "id": "SAL035",
    "studentName": "Akshay Kumar",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-13"
  },
  {
    "id": "SAL036",
    "studentName": "Ajay Devgn",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-14"
  },
  {
    "id": "SAL037",
    "studentName": "Sunil Shetty",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-15"
  },
  {
    "id": "SAL038",
    "studentName": "Sanjay Dutt",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-16"
  },
  {
    "id": "SAL039",
    "studentName": "Anil Kapoor",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-17"
  },
  {
    "id": "SAL040",
    "studentName": "Madhavan",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-18"
  },
  {
    "id": "SAL041",
    "studentName": "Surya Kumar",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-19"
  },
  {
    "id": "SAL042",
    "studentName": "Rohit Sharma",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-20"
  },
  {
    "id": "SAL043",
    "studentName": "Virat Kohli",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-21"
  },
  {
    "id": "SAL044",
    "studentName": "MS Dhoni",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-22"
  },
  {
    "id": "SAL045",
    "studentName": "Sachin Tendulkar",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-23"
  },
  {
    "id": "SAL046",
    "studentName": "Sourav Ganguly",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-01"
  },
  {
    "id": "SAL047",
    "studentName": "Rahul Dravid",
    "packageName": "Violin Starter 5-Class Package",
    "amount": 4500,
    "paymentMethod": "Net Banking",
    "date": "2026-07-02"
  },
  {
    "id": "SAL048",
    "studentName": "VVS Laxman",
    "packageName": "Vocal Basic 10-Class Package",
    "amount": 8500,
    "paymentMethod": "UPI (Google Pay)",
    "date": "2026-07-03"
  },
  {
    "id": "SAL049",
    "studentName": "Kapil Dev",
    "packageName": "Sitar Advanced 12-Class Package",
    "amount": 12000,
    "paymentMethod": "UPI (PhonePe)",
    "date": "2026-07-04"
  },
  {
    "id": "SAL050",
    "studentName": "Ananya Iyer",
    "packageName": "Tabla Intermediate 20-Class Package",
    "amount": 16000,
    "paymentMethod": "Credit Card",
    "date": "2026-07-05"
  }
];


// DEFAULT SEED ARRAYS FOR PORTAL ADDITIONS
const DEFAULT_LEADS = [
  { "id": "LD001", "name": "Rajesh Khanna", "phone": "+91 98234 56789", "email": "rajesh.khanna@example.com", "instrument": "Sitar", "status": "Inquiry", "date": "2026-07-25" },
  { "id": "LD002", "name": "Kiran Bedi", "phone": "+91 97345 67890", "email": "kiran.bedi@example.com", "instrument": "Vocal (Carnatic)", "status": "Demo Taken", "date": "2026-07-24" },
  { "id": "LD003", "name": "Vikram Ambalal", "phone": "+91 96456 78901", "email": "vikram.a@example.com", "instrument": "Violin", "status": "Converted to Student", "date": "2026-07-23", "amount": 4500, "paymentMethod": "NET BANKING", "transactionDate": "2026-07-23" },
  { "id": "LD004", "name": "Devi Prasad", "phone": "+91 95567 89012", "email": "devi.prasad@example.com", "instrument": "Tabla", "status": "Demo Failed", "date": "2026-07-22" },
  { "id": "LD005", "name": "Priyanka Gandhi", "phone": "+91 94678 90123", "email": "priyanka.g@example.com", "instrument": "Flute", "status": "Inquiry", "date": "2026-07-21" },
  { "id": "LD006", "name": "Amitabh Bach", "phone": "+91 93789 01234", "email": "bigb@example.com", "instrument": "Sitar", "status": "Demo Taken", "date": "2026-07-20" },
  { "id": "LD007", "name": "Sanjay Dutt", "phone": "+91 92890 12345", "email": "sanjay.d@example.com", "instrument": "Tabla", "status": "Converted to Student", "date": "2026-07-19", "amount": 16000, "paymentMethod": "UPI (PHONEPE)", "transactionDate": "2026-07-19" },
  { "id": "LD008", "name": "Sachin Ten", "phone": "+91 91901 23456", "email": "sachin.t@example.com", "instrument": "Vocal (Hindustani)", "status": "Demo Failed", "date": "2026-07-18" },
  { "id": "LD009", "name": "Lata Mangesh", "phone": "+91 90912 34567", "email": "lata.m@example.com", "instrument": "Vocal (Carnatic)", "status": "Inquiry", "date": "2026-07-17" },
  { "id": "LD010", "name": "A.R. Rahman", "phone": "+91 89923 45678", "email": "rahman@example.com", "instrument": "Piano/Vocal", "status": "Demo Taken", "date": "2026-07-16" }
];

const DEFAULT_REFERRALS = [
  { "id": "REF001", "referrerName": "Ananya Iyer", "referrerRole": "student", "referredName": "Aditya Roy", "referredEmail": "aditya.roy@example.com", "referredRole": "student", "status": "Approved", "date": "2026-07-26", "reward": "+2 Class Credits" },
  { "id": "REF002", "referrerName": "Ananya Iyer", "referrerRole": "student", "referredName": "Kunal Kapoor", "referredEmail": "kunal@example.com", "referredRole": "student", "status": "Pending", "date": "2026-07-27", "reward": "Awaiting Conversion" },
  { "id": "REF003", "referrerName": "Meera Sharma", "referrerRole": "teacher", "referredName": "Sneha Reddy", "referredEmail": "sneha@example.com", "referredRole": "student", "status": "Approved", "date": "2026-07-25", "reward": "₹500 Payout Bonus" },
  { "id": "REF004", "referrerName": "Meera Sharma", "referrerRole": "teacher", "referredName": "Rahul Sen", "referredEmail": "rahul.sen@example.com", "referredRole": "teacher", "status": "Pending", "date": "2026-07-28", "reward": "Awaiting Conversion" },
  { "id": "REF005", "referrerName": "Sarah Fernandez", "referrerRole": "student", "referredName": "Nikhil Dutt", "referredEmail": "nikhil@example.com", "referredRole": "student", "status": "Approved", "date": "2026-07-22", "reward": "+2 Class Credits" }
];

const DEFAULT_FEEDBACKS = [
  { "id": "FB001", "date": "2026-07-28 10:30", "studentName": "Ananya Iyer", "category": "Mentor", "target": "Meera Sharma", "rating": 5, "message": "Meera is an incredible mentor! Her lessons are extremely thorough and inspiring.", "status": "Active" },
  { "id": "FB002", "date": "2026-07-27 15:45", "studentName": "Aria Sharma", "category": "System", "target": "App Performance", "rating": 4, "message": "The live practice stream preloader takes a bit too long to fade out, but overall the system dashboard is fast.", "status": "Active" },
  { "id": "FB003", "date": "2026-07-26 18:20", "studentName": "Rohan Malhotra", "category": "Academy", "target": "Harita Overall", "rating": 5, "message": "Harita Music Academy is absolute heritage! Nurturing soulful talent and discipline.", "status": "Active" },
  { "id": "FB004", "date": "2026-07-25 11:10", "studentName": "Sarah Fernandez", "category": "Mentor", "target": "Meera Sharma", "rating": 5, "message": "I really enjoy my intermediate classes. Extremely detailed guidance.", "status": "Active" },
  { "id": "FB005", "date": "2026-07-24 14:00", "studentName": "Rahul Sen", "category": "System", "target": "App Feature Request", "rating": 3, "message": "Need calendar color indicators to stand out a bit more clearly.", "status": "Resolved" }
];

const DEFAULT_USERS = [
  { "id": "USR001", "name": "Super Administrator", "email": "admin@haritamusic.com", "password": "admin123", "role": "Admin", "status": "Active" },
  { "id": "USR002", "name": "Meera Sharma", "email": "meera.sharma@haritamusic.com", "password": "teacher123", "role": "Teacher", "status": "Active" },
  { "id": "USR003", "name": "Ananya Iyer", "email": "ananya.iyer@gmail.com", "password": "student123", "role": "Student", "status": "Active" },
  { "id": "USR004", "name": "Pandit Ravi Sen", "email": "ravi.sen@haritamusic.com", "password": "teacher456", "role": "Teacher", "status": "Active" },
  { "id": "USR005", "name": "Anjali Menon", "email": "anjali.menon@haritamusic.com", "password": "teacher789", "role": "Teacher", "status": "Active" },
  { "id": "USR006", "name": "Rohan Malhotra", "email": "rohan@gmail.com", "password": "student456", "role": "Student", "status": "Active" },
  { "id": "USR007", "name": "Kabir Mehta", "email": "kabir@gmail.com", "password": "student789", "role": "Student", "status": "Active" },
  { "id": "USR008", "name": "Sarah Fernandez", "email": "sarah.f@gmail.com", "password": "student101", "role": "Student", "status": "Active" },
  { "id": "USR009", "name": "Aria Sharma", "email": "aria@gmail.com", "password": "student202", "role": "Student", "status": "Inactive" },
  { "id": "USR010", "name": "Amit Patel", "email": "amit.patel@gmail.com", "password": "student303", "role": "Student", "status": "Active" }
];

const DEFAULT_GROUPS = [
  { "id": "GRP001", "name": "Vocal Quartet A", "students": ["STU001", "STU003"], "status": "Active" },
  { "id": "GRP002", "name": "Guitar Ensemble Alpha", "students": ["STU002"], "status": "Active" }
];

const DEFAULT_PAYROLL = [
  {
    "teacherName": "Meera Sharma",
    "monthYear": "July 2026",
    "classRate": 500,
    "classesTaken": 24,
    "opportunitiesTaken": 5,
    "daysTaught": [
      { "date": "2026-07-02", "classCount": 2, "hours": 2, "details": "Ananya Iyer (Vocal), Rajesh Khanna (Sitar)" },
      { "date": "2026-07-04", "classCount": 1, "hours": 1, "details": "Sarah Fernandez (Violin)" },
      { "date": "2026-07-07", "classCount": 3, "hours": 3, "details": "Rohan Malhotra (Sitar), Ananya Iyer (Vocal), Vikram Seth (Tabla)" },
      { "date": "2026-07-09", "classCount": 2, "hours": 2, "details": "Devi Prasad (Tabla), Priyanka Gandhi (Flute)" },
      { "date": "2026-07-11", "classCount": 2, "hours": 2, "details": "Aria Sharma (Vocal), Kunal Kapoor (Vocal)" },
      { "date": "2026-07-14", "classCount": 3, "hours": 3, "details": "Amitabh Bach (Sitar), Sanjay Dutt (Tabla), Ananya Iyer (Vocal)" },
      { "date": "2026-07-16", "classCount": 2, "hours": 2, "details": "Priya Nair (Violin), Lata Mangesh (Vocal)" },
      { "date": "2026-07-18", "classCount": 1, "hours": 1, "details": "Sachin Ten (Vocal)" },
      { "date": "2026-07-21", "classCount": 3, "hours": 3, "details": "A.R. Rahman (Piano), Aditya Roy (Sitar), Ananya Iyer (Vocal)" },
      { "date": "2026-07-23", "classCount": 2, "hours": 2, "details": "Nikhil Dutt (Sitar), Sneha Reddy (Vocal)" },
      { "date": "2026-07-25", "classCount": 2, "hours": 2, "details": "Kiran Bedi (Vocal), Sarah Fernandez (Violin)" },
      { "date": "2026-07-28", "classCount": 1, "hours": 1, "details": "Ananya Iyer (Vocal)" }
    ]
  }
];

// Initialize LocalStorage Data
function initializeDatabase() {
  const seedIfEmpty = (key, defaultData) => {
    const val = localStorage.getItem(key);
    if (!val || val === "undefined" || val === "null" || JSON.parse(val).length === 0) {
      localStorage.setItem(key, JSON.stringify(defaultData));
    }
  };

  seedIfEmpty("harita_students", DEFAULT_STUDENTS);
  seedIfEmpty("harita_teachers", DEFAULT_TEACHERS);
  seedIfEmpty("harita_classes", DEFAULT_CLASSES);
  seedIfEmpty("harita_leaves", DEFAULT_LEAVES);
  seedIfEmpty("harita_sales", DEFAULT_SALES);
  seedIfEmpty("harita_leads", DEFAULT_LEADS);
  seedIfEmpty("harita_referrals", DEFAULT_REFERRALS);
  seedIfEmpty("harita_feedbacks", DEFAULT_FEEDBACKS);
  seedIfEmpty("harita_payroll", DEFAULT_PAYROLL);
  seedIfEmpty("harita_users", DEFAULT_USERS);
  seedIfEmpty("harita_groups", DEFAULT_GROUPS);

  // Force re-seed of leads if old schema is detected
  const currentLeadsStr = localStorage.getItem("harita_leads");
  if (currentLeadsStr) {
    try {
      const currentLeads = JSON.parse(currentLeadsStr);
      const convertedVikram = currentLeads.find(l => l.id === "LD003");
      if (convertedVikram && !convertedVikram.amount) {
        localStorage.setItem("harita_leads", JSON.stringify(DEFAULT_LEADS));
      }
    } catch(e) {}
  }

  // Force re-seed of classes if default demo classes are not present
  const currentClassesStr = localStorage.getItem("harita_classes");
  if (currentClassesStr) {
    try {
      const currentClasses = JSON.parse(currentClassesStr);
      const hasDemos = currentClasses.some(c => c.isDemo);
      if (!hasDemos) {
        localStorage.setItem("harita_classes", JSON.stringify(DEFAULT_CLASSES));
      }
    } catch(e) {}
  }

  // Force re-seed of users if old 3-user seed is detected
  const currentUsersStr = localStorage.getItem("harita_users");
  if (currentUsersStr) {
    try {
      const currentUsers = JSON.parse(currentUsersStr);
      if (currentUsers.length < 5) {
        localStorage.setItem("harita_users", JSON.stringify(DEFAULT_USERS));
      }
    } catch(e) {}
  }

  // Self-heal teachers schema with weekOff field
  const currentTeachersStr = localStorage.getItem("harita_teachers");
  if (currentTeachersStr) {
    try {
      let currentTeachers = JSON.parse(currentTeachersStr);
      let changed = false;
      currentTeachers.forEach(t => {
        if (typeof t.weekOff === "undefined") {
          if (t.id === "TCH001") t.weekOff = ["Sun"];
          else if (t.id === "TCH002") t.weekOff = ["Mon"];
          else if (t.id === "TCH003") t.weekOff = ["Sat", "Sun"];
          else if (t.id === "TCH004") t.weekOff = ["Thu"];
          else t.weekOff = ["Sun"];
          changed = true;
        }
        if (typeof t.youtube === "undefined") {
          if (t.id === "TCH001") t.youtube = "https://www.youtube.com/watch?v=z4s2_aHn8v0";
          else if (t.id === "TCH002") t.youtube = "https://www.youtube.com/watch?v=9xB_X9BOAOU";
          else if (t.id === "TCH003") t.youtube = "https://www.youtube.com/watch?v=jW8mG2cT240";
          else t.youtube = "";
          changed = true;
        }
        if (typeof t.certifications === "undefined") {
          if (t.id === "TCH001") t.certifications = "Carnatic Vocal Acharya, Academy Representative";
          else if (t.id === "TCH002") t.certifications = "Sitar Maestro Certification";
          else if (t.id === "TCH003") t.certifications = "Violin Visharad, Trinity Grade 8";
          else t.certifications = "";
          changed = true;
        }
      });
      if (changed) {
        localStorage.setItem("harita_teachers", JSON.stringify(currentTeachers));
      }
    } catch(e) {}
  }

  // Self-heal students schema with enrolledAs and groupId fields
  const currentStudentsStr = localStorage.getItem("harita_students");
  if (currentStudentsStr) {
    try {
      let currentStudents = JSON.parse(currentStudentsStr);
      let changed = false;
      currentStudents.forEach(s => {
        if (typeof s.enrolledAs === "undefined") {
          if (s.id === "STU001" || s.id === "STU003") {
            s.enrolledAs = "Group";
            s.groupId = "GRP001";
          } else if (s.id === "STU002") {
            s.enrolledAs = "Group";
            s.groupId = "GRP002";
          } else {
            s.enrolledAs = "Individual";
            s.groupId = "";
          }
          changed = true;
        }
        if (typeof s.youtube === "undefined") {
          if (s.id === "STU001") s.youtube = "https://www.youtube.com/watch?v=Vl3G_7hR9qQ";
          else s.youtube = "";
          changed = true;
        }
      });
      if (changed) {
        localStorage.setItem("harita_students", JSON.stringify(currentStudents));
      }
    } catch(e) {}
  }
  
  // Auto sync role state with physical folder path on page load
  const currentPath = window.location.pathname;
  if (currentPath.includes("/admin/")) {
    localStorage.setItem("harita_role", "admin");
  } else if (currentPath.includes("/teacher/")) {
    localStorage.setItem("harita_role", "teacher");
  } else if (currentPath.includes("/student/")) {
    localStorage.setItem("harita_role", "student");
  } else if (!localStorage.getItem("harita_role")) {
    localStorage.setItem("harita_role", "admin");
  }
}

// Global state helper getters
const db = {
  getUsers: () => JSON.parse(localStorage.getItem("harita_users")) || [],
  getGroups: () => JSON.parse(localStorage.getItem("harita_groups")) || [],
  setGroups: (data) => localStorage.setItem("harita_groups", JSON.stringify(data)),
  setUsers: (data) => localStorage.setItem("harita_users", JSON.stringify(data)),
  getStudents: () => JSON.parse(localStorage.getItem("harita_students")),
  setStudents: (data) => localStorage.setItem("harita_students", JSON.stringify(data)),
  getTeachers: () => JSON.parse(localStorage.getItem("harita_teachers")),
  setTeachers: (data) => localStorage.setItem("harita_teachers", JSON.stringify(data)),
  getClasses: () => JSON.parse(localStorage.getItem("harita_classes")),
  setClasses: (data) => localStorage.setItem("harita_classes", JSON.stringify(data)),
  getLeaves: () => JSON.parse(localStorage.getItem("harita_leaves")),
  setLeaves: (data) => localStorage.setItem("harita_leaves", JSON.stringify(data)),
  getSales: () => JSON.parse(localStorage.getItem("harita_sales")),
  setSales: (data) => localStorage.setItem("harita_sales", JSON.stringify(data)),
  getLeads: () => JSON.parse(localStorage.getItem("harita_leads")),
  setLeads: (data) => localStorage.setItem("harita_leads", JSON.stringify(data)),
  getReferrals: () => JSON.parse(localStorage.getItem("harita_referrals")),
  setReferrals: (data) => localStorage.setItem("harita_referrals", JSON.stringify(data)),
  getFeedbacks: () => JSON.parse(localStorage.getItem("harita_feedbacks")),
  setFeedbacks: (data) => localStorage.setItem("harita_feedbacks", JSON.stringify(data)),
  getPayroll: () => JSON.parse(localStorage.getItem("harita_payroll")),
  setPayroll: (data) => localStorage.setItem("harita_payroll", JSON.stringify(data)),
  getCurrentRole: () => localStorage.getItem("harita_role") || "admin",
  setCurrentRole: (role) => {
    localStorage.setItem("harita_role", role);
    window.dispatchEvent(new Event("roleChanged"));
  }
};

// Auto run on load
initializeDatabase();

/* ==========================================
   COMMON LAYOUT & DYNAMIC BEHAVIOR
   ========================================== */
document.addEventListener("DOMContentLoaded", () => {
  initializeSidebarState();
  setupSidebarNavigation();
  // setupRoleSwitcher(); // Disabled the demo persona switcher tabs
  setupSidebarCollapsible();
  setupDropdowns();
  updateUserInfoLayout();
  
  // Reload page state when role is changed globally
  window.addEventListener("roleChanged", () => {
    updateUserInfoLayout();
    // applyRoleBasedVisibility();
    
    // If a page has a custom reload callback, run it
    if (typeof onRoleChange === "function") {
      onRoleChange();
    } else {
      // Refresh current page to apply template changes
      window.location.reload();
    }
  });

  // applyRoleBasedVisibility();
  setupPrivacyPolicy();
});

// Preloader Fade-out Event Listener
window.addEventListener("load", () => {
  const preloader = document.getElementById("preloader");
  if (preloader) {
    preloader.classList.add("fade-out");
    setTimeout(() => {
      preloader.style.display = "none";
    }, 450);
  }
});

// Setup sidebar toggle for mobile drawer
function setupSidebarCollapsible() {
  const toggleBtn = document.querySelector(".menu-toggle");
  const sidebar = document.querySelector(".sidebar");
  
  if (!toggleBtn || !sidebar) return;

  // Create overlay backdrop dynamically if not exists
  let backdrop = document.querySelector(".sidebar-backdrop");
  if (!backdrop) {
    backdrop = document.createElement("div");
    backdrop.className = "sidebar-backdrop";
    document.body.appendChild(backdrop);
  }

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("show");
    backdrop.classList.toggle("show");
  });

  backdrop.addEventListener("click", () => {
    sidebar.classList.remove("show");
    backdrop.classList.remove("show");
  });
}

// Highlight current page in Sidebar Navigation
function setupSidebarNavigation() {
  const currentPath = window.location.pathname;
  const pageName = currentPath.substring(currentPath.lastIndexOf("/") + 1) || "dashboard.html";
  const sidebarMenu = document.querySelector(".sidebar-menu");

  if (sidebarMenu) {
    // Check role from path
    if (currentPath.includes("/student/")) {
      // Add Refer & Earn if not present
      if (!document.getElementById("nav-referrals")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-referrals";
        li.innerHTML = `
          <a href="/admin/referrals" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Refer & Earn</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
      // Add Feedback if not present
      if (!document.getElementById("nav-feedback")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-feedback";
        li.innerHTML = `
          <a href="/admin/feedbacks" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Feedback</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
    } else if (currentPath.includes("/teacher/")) {
      // Add Referrals
      if (!document.getElementById("nav-referrals")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-referrals";
        li.innerHTML = `
          <a href="/admin/referrals" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Referrals</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
      // Add Feedback
      if (!document.getElementById("nav-feedbacks")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-feedbacks";
        li.innerHTML = `
          <a href="/admin/feedbacks" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Student Feedback</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
      // Add Payroll
      if (!document.getElementById("nav-payroll")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-payroll";
        li.innerHTML = `
          <a href="/admin/payroll" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            <span>My Payroll</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
    } else if (currentPath.includes("/admin/")) {
      // Add Feedbacks to Admin
      if (!document.getElementById("nav-feedbacks")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-feedbacks";
        li.innerHTML = `
          <a href="/admin/feedbacks" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Feedbacks</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
      // Add Payroll to Admin
      if (!document.getElementById("nav-payroll")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-payroll";
        li.innerHTML = `
          <a href="/admin/payroll" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            <span>Payroll</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
      // Add Demo Classes to Admin
      if (!document.getElementById("nav-demos")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-demos";
        li.innerHTML = `
          <a href="/admin/demos" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <span>Demo Classes</span>
          </a>
        `;
        const bookingItem = document.getElementById("nav-booking");
        if (bookingItem) sidebarMenu.insertBefore(li, bookingItem);
        else sidebarMenu.appendChild(li);
      }
      
      // Add Referrals to Admin
      if (!document.getElementById("nav-referrals")) {
        const li = document.createElement("li");
        li.className = "sidebar-item";
        li.id = "nav-referrals";
        li.innerHTML = `
          <a href="/admin/referrals" class="sidebar-item-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Referral Dashboard</span>
          </a>
        `;
        const settingsItem = document.getElementById("nav-settings");
        if (settingsItem) sidebarMenu.insertBefore(li, settingsItem);
        else sidebarMenu.appendChild(li);
      }
    }
  }

  const menuLinks = document.querySelectorAll(".sidebar-menu .sidebar-item");
  menuLinks.forEach(item => {
    const link = item.querySelector("a");
    if (!link) return;
    const href = link.getAttribute("href");
    if (href === pageName || (pageName === "" && href === "dashboard.html")) {
      item.classList.add("active");
    } else {
      item.classList.remove("active");
    }
    
    // Automatically set title attribute for collapsed hover tooltips
    const span = link.querySelector("span");
    if (span && !link.hasAttribute("title")) {
      link.setAttribute("title", span.textContent.trim());
    }
  });
}

// Setup dropdown toggles (notifications and profile menu)
function setupDropdowns() {
  const dropdownTriggers = document.querySelectorAll("[data-toggle='dropdown']");
  
  dropdownTriggers.forEach(trigger => {
    trigger.addEventListener("click", (e) => {
      e.stopPropagation();
      const targetId = trigger.getAttribute("data-target");
      const targetMenu = document.getElementById(targetId);
      
      // Close other open dropdowns first
      document.querySelectorAll(".dropdown-menu").forEach(menu => {
        if (menu !== targetMenu) menu.classList.remove("show");
      });

      if (targetMenu) {
        targetMenu.classList.toggle("show");
      }
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener("click", () => {
    document.querySelectorAll(".dropdown-menu").forEach(menu => {
      menu.classList.remove("show");
    });
  });
}

// Simulated Role Switcher Widget in header
function setupRoleSwitcher() {
  const headerRight = document.querySelector(".header-right");
  if (!headerRight) return;

  // Check if switcher already exists
  if (document.querySelector(".role-switch-container")) return;

  const currentRole = db.getCurrentRole();

  const container = document.createElement("div");
  container.className = "role-switch-container";
  container.innerHTML = `
    <button class="role-switch-btn ${currentRole === 'admin' ? 'active' : ''}" data-role="admin">Admin</button>
    <button class="role-switch-btn ${currentRole === 'teacher' ? 'active' : ''}" data-role="teacher">Teacher</button>
    <button class="role-switch-btn ${currentRole === 'student' ? 'active' : ''}" data-role="student">Student</button>
  `;

  // Insert before the notifications button
  const firstChild = headerRight.firstChild;
  headerRight.insertBefore(container, firstChild);

  // Add click listeners
  container.querySelectorAll(".role-switch-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const selectedRole = btn.getAttribute("data-role");
      container.querySelectorAll(".role-switch-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      db.setCurrentRole(selectedRole);
      
      // Redirect to the correct subfolder dashboard
      const currentPath = window.location.pathname;
      const isSubfolder = currentPath.includes("/admin/") || currentPath.includes("/teacher/") || currentPath.includes("/student/");
      const prefix = isSubfolder ? "../" : "";
      window.location.href = prefix + selectedRole + "/dashboard.html";
    });
  });
}

// Update the user card display on sidebar and dropdown based on role
function updateUserInfoLayout() {
  const role = db.getCurrentRole();
  const nameLabel = document.querySelector(".sidebar-user-name");
  const roleLabel = document.querySelector(".sidebar-user-role");

  let userName = "Administrator";
  let userInitials = "AD";
  let displayRole = "Super Admin";

  if (role === "teacher") {
    userName = "Meera Sharma";
    userInitials = "MS";
    displayRole = "Vocal Lead";
  } else if (role === "student") {
    userName = "Ananya Iyer";
    userInitials = "AI";
    displayRole = "Carnatic Student";
  }

  if (nameLabel) nameLabel.textContent = userName;
  if (roleLabel) roleLabel.textContent = displayRole;
  
  // Render custom profile image if stored
  const savedImg = localStorage.getItem("harita_profile_image_" + role);
  
  document.querySelectorAll(".avatar").forEach(avatarEl => {
    avatarEl.innerHTML = "";
    if (savedImg) {
      const img = document.createElement("img");
      img.src = savedImg;
      img.style.width = "100%";
      img.style.height = "100%";
      img.style.borderRadius = "50%";
      img.style.objectFit = "cover";
      avatarEl.appendChild(img);
    } else {
      avatarEl.textContent = userInitials;
    }
  });
}

// Apply visual filters and menu item visibility depending on the simulated role
function applyRoleBasedVisibility() {
  const role = db.getCurrentRole();
  
  // Select items that are only for Admin
  const adminOnlyElements = document.querySelectorAll("[data-role-limit='admin']");
  const teacherOnlyElements = document.querySelectorAll("[data-role-limit='teacher']");
  const studentOnlyElements = document.querySelectorAll("[data-role-limit='student']");

  // Show/Hide sidebar options or other sections depending on role
  adminOnlyElements.forEach(el => {
    el.style.display = (role === "admin") ? "" : "none";
  });

  teacherOnlyElements.forEach(el => {
    el.style.display = (role === "teacher") ? "" : "none";
  });

  studentOnlyElements.forEach(el => {
    el.style.display = (role === "student") ? "" : "none";
  });

  // Let's restrict side menu entries based on role dynamically:
  const menuItems = document.querySelectorAll(".sidebar-menu .sidebar-item");
  menuItems.forEach(item => {
    const link = item.querySelector("a");
    if (!link) return;
    const page = link.getAttribute("href");
    
    // Admin only pages
    if (["students.html", "teachers.html", "sales.html", "roles.html", "reports.html", "credits.html", "demos.html"].includes(page)) {
      item.style.display = (role === "admin") ? "" : "none";
    }
    // Feedbacks & Payroll visibility
    if (["feedbacks.html", "payroll.html"].includes(page)) {
      const isTeacherFolder = window.location.pathname.includes("/teacher/");
      item.style.display = (role === "admin" || isTeacherFolder) ? "" : "none";
    }
    // Referrals visibility
    if (["referrals.html"].includes(page)) {
      const isTeacherOrStudent = window.location.pathname.includes("/teacher/") || window.location.pathname.includes("/student/");
      item.style.display = (role === "admin" || isTeacherOrStudent) ? "" : "none";
    }
    // Teacher & Admin page
    if (["leaves.html"].includes(page)) {
      item.style.display = (role === "admin" || role === "teacher") ? "" : "none";
    }
    // Teacher & Student pages
    if (["my-classes.html"].includes(page)) {
      item.style.display = (role === "teacher" || role === "student") ? "" : "none";
    }
  });
}

// Helper to initialize or re-initialize DataTables dynamically
function setupDataTable(tableId, customOptions = {}) {
  if (typeof $ === "undefined" || !$.fn.DataTable) {
    console.warn("jQuery or DataTables library is not loaded.");
    return null;
  }
  
  // If exists, destroy first to allow redraws
  if ($.fn.DataTable.isDataTable("#" + tableId)) {
    $("#" + tableId).DataTable().destroy();
  }

  const defaultOptions = {
    responsive: true,
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50],
    language: {
      search: "",
      searchPlaceholder: "Search records...",
      lengthMenu: "Show _MENU_ rows",
      paginate: {
        previous: "Prev",
        next: "Next"
      }
    }
  };

  const finalOptions = $.extend(true, {}, defaultOptions, customOptions);
  const dtInstance = $("#" + tableId).DataTable(finalOptions);

  // Remove default text node labels inside search filter label to leave only the input
  $(".dataTables_filter label").contents().filter(function() {
    return this.nodeType === 3;
  }).remove();

  return dtInstance;
}

/* ==========================================
   MODAL UTILITY HELPERS
   ========================================== */
function showModal(modalId) {
  const backdrop = document.getElementById(modalId);
  if (backdrop) {
    backdrop.classList.add("show");
  }
}

function hideModal(modalId) {
  const backdrop = document.getElementById(modalId);
  if (backdrop) {
    backdrop.classList.remove("show");
  }
}

// Bind close button listeners in modals
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".modal-backdrop").forEach(backdrop => {
    backdrop.addEventListener("click", (e) => {
      if (e.target === backdrop) {
        backdrop.classList.remove("show");
      }
    });

    const closeBtn = backdrop.querySelector(".modal-close, [data-dismiss='modal']");
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        backdrop.classList.remove("show");
      });
    }
  });
});

/* ==========================================
   DESKTOP COLLAPSE STATE & DROPDOWN MANAGEMENT
   ========================================== */
function initializeSidebarState() {
  const container = document.querySelector(".app-container");
  const isCollapsed = localStorage.getItem("harita_sidebar_collapsed") === "true";
  
  if (container) {
    if (isCollapsed) {
      container.classList.add("sidebar-collapsed");
      updateCollapseArrow(true);
    } else {
      container.classList.remove("sidebar-collapsed");
      updateCollapseArrow(false);
    }
  }
}

function toggleSidebarCollapse() {
  const container = document.querySelector(".app-container");
  if (!container) return;
  
  container.classList.toggle("sidebar-collapsed");
  const isCollapsed = container.classList.contains("sidebar-collapsed");
  localStorage.setItem("harita_sidebar_collapsed", isCollapsed ? "true" : "false");
  updateCollapseArrow(isCollapsed);
}

function updateCollapseArrow(isCollapsed) {
  // Sidenavbar fold toggle uses stable menu bar icon now, no arrow toggling needed
}

function toggleActionsDropdown(event, btnElement) {
  event.stopPropagation();
  const dropdownMenu = btnElement.nextElementSibling;
  
  // Close other open kebab menus first
  document.querySelectorAll(".actions-dropdown-menu").forEach(menu => {
    if (menu !== dropdownMenu) {
      menu.classList.remove("show");
    }
  });

  if (dropdownMenu) {
    dropdownMenu.classList.toggle("show");
  }
}

// Dismiss open action lists when clicking outside
document.addEventListener("click", () => {
  document.querySelectorAll(".actions-dropdown-menu").forEach(menu => {
    menu.classList.remove("show");
  });
});

/* ==========================================
   HARITA MUSIC ACADEMY - POLICY MANAGEMENT
   ========================================== */
const POLICY_STUDENT = `
<h4 style="font-weight:700; margin-bottom:1rem; color:var(--primary); text-transform:uppercase; font-family:var(--font-serif);">Student Policy</h4>
<ol style="padding-left:1.5rem; margin:0; list-style-type:decimal;">
  <li style="margin-bottom:0.75rem;"><strong>Acceptance of Academy Policies</strong>: By registering with Harita Music Academy, every student agrees to comply with all Academy policies, guidelines, and future updates. Continued use of Academy services constitutes acceptance of these policies.</li>
  <li style="margin-bottom:0.75rem;"><strong>Student Eligibility</strong>: Students must provide accurate personal information during registration. Any false or misleading information may result in account suspension or termination.</li>
  <li style="margin-bottom:0.75rem;"><strong>Respectful Behaviour</strong>: Every student is expected to maintain courtesy, respect, and professionalism towards teachers, Academy staff, and fellow students at all times.</li>
  <li style="margin-bottom:0.75rem;"><strong>Zero Tolerance for Misconduct</strong>: Abusive language, harassment, threats, bullying, discrimination, inappropriate behaviour, or any action that disrupts the learning environment will not be tolerated.</li>
  <li style="margin-bottom:0.75rem;"><strong>Professional Classroom Etiquette</strong>: Students should attend classes with proper discipline, remain attentive, avoid unnecessary interruptions, and contribute positively to the learning environment.</li>
  <li style="margin-bottom:0.75rem;"><strong>Attendance</strong>: Regular attendance is essential for consistent progress. Students are responsible for attending every scheduled class on time.</li>
  <li style="margin-bottom:0.75rem;"><strong>Punctuality</strong>: Students are advised to join the class at least 5 minutes before the scheduled time. Late entry may reduce the effective learning duration and repeated delays may affect learning progress.</li>
  <li style="margin-bottom:0.75rem;"><strong>Class Cancellation & Rescheduling</strong>: Class cancellation or rescheduling requests must be submitted at least 10 hours before the scheduled class. Requests received after this period will not be accepted.</li>
  <li style="margin-bottom:0.75rem;"><strong>No Show Policy</strong>: Failure to attend a scheduled class without prior notice will be recorded as a No Show. The class credit will be considered consumed and no refund, replacement, or rescheduling will be provided.</li>
  <li style="margin-bottom:0.75rem;"><strong>Class Credits</strong>: Class credits are personal, non-transferable, and cannot be exchanged for cash. Credits remain valid only within the purchased package validity.</li>
  <li style="margin-bottom:0.75rem;"><strong>Refund Policy</strong>: Course fees, class credits, and purchased packages are generally non-refundable unless specifically approved under the Academy's official Refund Policy.</li>
  <li style="margin-bottom:0.75rem;"><strong>Payment Responsibility</strong>: Students are responsible for ensuring timely payment of all applicable fees. Access to Academy services may be restricted until outstanding payments are cleared.</li>
  <li style="margin-bottom:0.75rem;"><strong>Demo Class Policy</strong>: Demo classes are intended solely for evaluation purposes and are governed by the Academy's demo class guidelines.</li>
  <li style="margin-bottom:0.75rem;"><strong>Learning Environment</strong>: Students must attend classes from a quiet, distraction-free environment with a stable internet connection, functional microphone, and suitable learning setup.</li>
  <li style="margin-bottom:0.75rem;"><strong>Recording & Copyright</strong>: Recording, downloading, sharing, reproducing, or distributing any Academy class, study material, or digital content without prior written permission is strictly prohibited.</li>
  <li style="margin-bottom:0.75rem;"><strong>Study Material Usage</strong>: All PDFs, recordings, videos, exercises, and learning resources are provided exclusively for personal educational use and remain the intellectual property of Harita Music Academy.</li>
  <li style="margin-bottom:0.75rem;"><strong>Communication</strong>: All Academy-related communication should take place through authorised Academy platforms. Respectful communication is expected at all times.</li>
  <li style="margin-bottom:0.75rem;"><strong>Privacy & Account Security</strong>: Students are responsible for maintaining the confidentiality of their login credentials. Account sharing is strictly prohibited.</li>
  <li style="margin-bottom:0.75rem;"><strong>Technical Responsibility</strong>: Students are responsible for maintaining a reliable internet connection and compatible devices. Technical issues on the student's side shall not qualify for compensation or replacement classes.</li>
  <li style="margin-bottom:0.75rem;"><strong>Parent/Guardian Responsibility</strong>: For minor students, parents or guardians are expected to maintain respectful communication with Academy staff and support a positive learning environment.</li>
  <li style="margin-bottom:0.75rem;"><strong>Weekly Feedback</strong>: Students may be invited to submit weekly feedback after attending classes. Constructive feedback helps improve the overall learning experience.</li>
  <li style="margin-bottom:0.75rem;"><strong>Platform Misuse</strong>: Unauthorised access, impersonation, spam, fraudulent activity, or misuse of Academy systems is strictly prohibited.</li>
  <li style="margin-bottom:0.75rem;"><strong>Policy Violations</strong>: Violation of Academy policies may result in verbal warnings, written warnings, temporary suspension, cancellation of class credits, or permanent account termination, depending on the severity of the violation.</li>
  <li style="margin-bottom:0.75rem;"><strong>Academy Authority</strong>: Harita Music Academy reserves the right to modify policies, schedules, faculty assignments, fees, or operational procedures whenever necessary. The Academy's decision regarding policy interpretation and disciplinary matters shall remain final and binding.</li>
</ol>
`;

const POLICY_TEACHER = `
<h4 style="font-weight:700; margin-bottom:1rem; color:var(--primary); text-transform:uppercase; font-family:var(--font-serif);">Teacher Policy</h4>
<ol style="padding-left:1.5rem; margin:0; list-style-type:decimal;">
  <li style="margin-bottom:0.75rem;"><strong>Acceptance of Policies</strong>: By joining Harita Music Academy, every teacher agrees to comply with all Academy policies, operational guidelines, and future updates.</li>
  <li style="margin-bottom:0.75rem;"><strong>Professional Conduct</strong>: Teachers shall maintain the highest standards of professionalism, integrity, and ethical behaviour at all times.</li>
  <li style="margin-bottom:0.75rem;"><strong>Respectful Behaviour</strong>: Teachers must treat every student, parent, colleague, and Academy representative with dignity, patience, and respect.</li>
  <li style="margin-bottom:0.75rem;"><strong>Zero Tolerance for Misconduct</strong>: Harassment, abusive language, discrimination, intimidation, threats, inappropriate behaviour, or unprofessional conduct will not be tolerated under any circumstances.</li>
  <li style="margin-bottom:0.75rem;"><strong>Punctuality</strong>: Teachers must join every scheduled class at least 5 minutes before the class begins.</li>
  <li style="margin-bottom:0.75rem;"><strong>Class Responsibility</strong>: Every scheduled class must be conducted with proper preparation, discipline, and commitment to the approved curriculum.</li>
  <li style="margin-bottom:0.75rem;"><strong>Attendance Submission</strong>: Attendance must be marked accurately immediately after each class. False attendance records are considered a serious policy violation.</li>
  <li style="margin-bottom:0.75rem;"><strong>Leave Request</strong>: Planned leave or class cancellation requests must be submitted at least 6 hours before the scheduled class for approval.</li>
  <li style="margin-bottom:0.75rem;"><strong>Teacher No Show</strong>: Failure to conduct an assigned class without prior approval will be recorded as a Teacher No Show. A ₹500 penalty may be applied for each confirmed violation.</li>
  <li style="margin-bottom:0.75rem;"><strong>Late Joining</strong>: Repeated late joining affects student learning and may lead to warnings, performance review, or disciplinary action.</li>
  <li style="margin-bottom:0.75rem;"><strong>Class Cancellation</strong>: Teachers shall not cancel classes without valid reasons and prior approval from the Academy except in genuine emergencies.</li>
  <li style="margin-bottom:0.75rem;"><strong>Student Progress</strong>: Teachers are responsible for monitoring student progress, maintaining lesson continuity, and providing constructive guidance.</li>
  <li style="margin-bottom:0.75rem;"><strong>Teaching Quality</strong>: Every class must meet the Academy's expected standards of quality, professionalism, and student engagement.</li>
  <li style="margin-bottom:0.75rem;"><strong>Communication</strong>: All communication with students and parents must remain professional and should take place only through authorised Academy channels.</li>
  <li style="margin-bottom:0.75rem;"><strong>Student Privacy</strong>: Teachers shall protect the confidentiality of all student information, academic records, and personal data.</li>
  <li style="margin-bottom:0.75rem;"><strong>Recording & Intellectual Property</strong>: Academy curriculum, recordings, lesson plans, presentations, PDFs, and all educational resources remain the exclusive intellectual property of Harita Music Academy and may not be copied, distributed, or used outside the Academy without written permission.</li>
  <li style="margin-bottom:0.75rem;"><strong>Conflict of Interest</strong>: Teachers shall not encourage, solicit, or transfer Academy students to personal tuition, private classes, or competing platforms.</li>
  <li style="margin-bottom:0.75rem;"><strong>Financial Conduct</strong>: Teachers are strictly prohibited from accepting direct payments, gifts in exchange for services, or conducting private financial transactions with Academy students.</li>
  <li style="margin-bottom:0.75rem;"><strong>Professional Appearance</strong>: Teachers are expected to maintain a neat, professional appearance and ensure an appropriate teaching environment during online classes.</li>
  <li style="margin-bottom:0.75rem;"><strong>Technical Responsibility</strong>: Teachers must ensure a stable internet connection, clear audio, and suitable teaching equipment before every class.</li>
  <li style="margin-bottom:0.75rem;"><strong>Performance Review</strong>: Teaching quality, punctuality, attendance, student feedback, and overall professionalism may be reviewed periodically by the Academy.</li>
  <li style="margin-bottom:0.75rem;"><strong>Policy Violations</strong>: Depending on the severity of the violation, disciplinary actions may include verbal warning, written warning, ₹500 penalty, temporary suspension, payment review, or permanent termination of association.</li>
  <li style="margin-bottom:0.75rem;"><strong>Emergency Situations</strong>: In exceptional emergencies, teachers must inform the Academy immediately so that alternative teaching arrangements can be made.</li>
  <li style="margin-bottom:0.75rem;"><strong>Academy Rights</strong>: Harita Music Academy reserves the right to modify schedules, class allocations, operational procedures, and teaching assignments whenever required.</li>
  <li style="margin-bottom:0.75rem;"><strong>Final Decision</strong>: All decisions regarding teacher performance, disciplinary matters, penalties, suspensions, and policy interpretation shall be made solely by Harita Music Academy and shall remain final and binding.</li>
</ol>
`;

const POLICY_STAFF = `
<h4 style="font-weight:700; margin-bottom:1rem; color:var(--primary); text-transform:uppercase; font-family:var(--font-serif);">Non-Teaching Staff Policy</h4>
<ol style="padding-left:1.5rem; margin:0; list-style-type:decimal;">
  <li style="margin-bottom:0.75rem;"><strong>Acceptance of Policy</strong>: All staff members are required to comply with the Academy's policies, procedures, and ethical standards.</li>
  <li style="margin-bottom:0.75rem;"><strong>Professional Conduct</strong>: Every employee shall perform their duties with honesty, professionalism, integrity, and accountability.</li>
  <li style="margin-bottom:0.75rem;"><strong>Respectful Behaviour</strong>: Respectful communication with students, parents, teachers, colleagues, and management is mandatory at all times.</li>
  <li style="margin-bottom:0.75rem;"><strong>Zero Tolerance for Misconduct</strong>: Abusive language, harassment, discrimination, bullying, threats, or any inappropriate behaviour will lead to disciplinary action.</li>
  <li style="margin-bottom:0.75rem;"><strong>Punctuality & Attendance</strong>: Employees are expected to report on time, complete assigned duties responsibly, and maintain regular attendance.</li>
  <li style="margin-bottom:0.75rem;"><strong>Confidentiality</strong>: All student, teacher, financial, operational, and business information must remain strictly confidential during and after employment.</li>
  <li style="margin-bottom:0.75rem;"><strong>Honest Communication</strong>: False promises, misleading information, or unauthorised commitments to students or parents are strictly prohibited.</li>
  <li style="margin-bottom:0.75rem;"><strong>Sales & Admission Ethics</strong>: Admissions and counselling must be conducted honestly. Misrepresentation for personal targets or incentives is not permitted.</li>
  <li style="margin-bottom:0.75rem;"><strong>Financial Integrity</strong>: No employee may collect personal payments, accept unauthorised cash, or misuse Academy funds or resources.</li>
  <li style="margin-bottom:0.75rem;"><strong>Academy Property</strong>: All documents, software, login credentials, equipment, and digital resources remain the property of Harita Music Academy and must be protected at all times.</li>
  <li style="margin-bottom:0.75rem;"><strong>Conflict of Interest</strong>: Employees shall not promote competing businesses, misuse Academy contacts, or recruit Academy students or teachers for personal benefit.</li>
  <li style="margin-bottom:0.75rem;"><strong>Data & System Security</strong>: Unauthorised access, sharing, copying, or misuse of Academy data or systems is strictly prohibited.</li>
  <li style="margin-bottom:0.75rem;"><strong>Social Media & Public Conduct</strong>: Employees shall not publish confidential information or make public statements that may harm the Academy's reputation.</li>
  <li style="margin-bottom:0.75rem;"><strong>Performance & Responsibility</strong>: Employees are expected to perform their assigned responsibilities efficiently, maintain work quality, and cooperate with their team.</li>
  <li style="margin-bottom:0.75rem;"><strong>Policy Violations</strong>: Depending on the seriousness of the violation, disciplinary action may include a verbal warning, written warning, suspension, salary deduction where legally applicable, or termination of employment.</li>
  <li style="margin-bottom:0.75rem;"><strong>Academy Authority</strong>: Harita Music Academy reserves the right to modify policies, assign responsibilities, review employee performance, and take disciplinary action whenever necessary. The Academy's decision shall be final and binding.</li>
</ol>
`;

function setupPrivacyPolicy() {
  // 1. Inject the Privacy Policy Link in Footer
  const footerElement = document.querySelector(".footer p") || document.querySelector(".login-footer p");
  if (footerElement && !document.getElementById("policyLink")) {
    const divider = document.createTextNode(" | ");
    const policyLink = document.createElement("a");
    policyLink.id = "policyLink";
    policyLink.href = "javascript:void(0)";
    policyLink.textContent = "Privacy Policy";
    policyLink.style.fontWeight = "600";
    policyLink.style.cursor = "pointer";
    policyLink.onclick = () => openPrivacyPolicyModal(false);
    
    footerElement.appendChild(divider);
    footerElement.appendChild(policyLink);
  }

  // 2. Inject Modal markup in document body if not exists
  if (!document.getElementById("policyModal")) {
    const modalDiv = document.createElement("div");
    modalDiv.id = "policyModal";
    modalDiv.className = "modal-backdrop";
    modalDiv.style.zIndex = "9999";
    modalDiv.innerHTML = `
      <div class="modal" style="max-width: 650px; width: 90%;">
        <div class="modal-header">
          <h3 class="font-semibold text-serif" style="font-size: 1.25rem; color: var(--primary);">Harita Music Academy Policy Guidelines</h3>
          <button class="modal-close" id="policyModalCloseBtn" onclick="closePrivacyPolicyModal()">×</button>
        </div>
        <div class="modal-body" id="policyModalBody" style="max-height: 420px; overflow-y: auto; line-height: 1.6; font-size: 13.5px; padding: 1.25rem; color: var(--text-main);">
          <!-- Policy content goes here -->
        </div>
        <div class="modal-footer" id="policyModalFooter" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
          <button type="button" class="btn btn-secondary" onclick="closePrivacyPolicyModal()">Close</button>
        </div>
      </div>
    `;
    document.body.appendChild(modalDiv);
  }

  // 3. Post-Login auto-popup logic on dashboard load
  const currentPath = window.location.pathname;
  const isDashboard = currentPath.includes("dashboard.html");
  if (isDashboard) {
    const role = db.getCurrentRole();
    const hasAccepted = localStorage.getItem("harita_policy_accepted_" + role) === "true";
    if (!hasAccepted) {
      setTimeout(() => {
        openPrivacyPolicyModal(true);
      }, 500);
    }
  }
}

function openPrivacyPolicyModal(forceAccept = false) {
  const role = db.getCurrentRole();
  const modalBody = document.getElementById("policyModalBody");
  const modalHeader = document.querySelector("#policyModal .modal-header h3");
  const footer = document.getElementById("policyModalFooter");
  const closeBtn = document.getElementById("policyModalCloseBtn");
  const modal = document.getElementById("policyModal");
  
  if (!modal || !modalBody) return;

  let content = "";
  let title = "Academy Policy Guidelines";
  
  if (role === "student") {
    content = POLICY_STUDENT;
    title = "Student Policy";
  } else if (role === "teacher") {
    content = POLICY_TEACHER;
    title = "Teacher Policy";
  } else {
    content = POLICY_STAFF;
    title = "Non-Teaching Staff Policy";
  }

  modalBody.innerHTML = content;
  if (modalHeader) modalHeader.textContent = title;

  if (forceAccept) {
    if (closeBtn) closeBtn.style.display = "none";
    footer.innerHTML = `
      <div style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
        <p style="font-size: 11.5px; color: var(--text-muted); text-align: left; margin: 0; line-height: 1.4;">By clicking Accept & Proceed, you acknowledge that you have read and agree to comply with Harita Music Academy's official policy guidelines.</p>
        <button type="button" class="btn btn-primary" style="width: 100%; margin-top: 0.25rem;" onclick="acceptPrivacyPolicy()">Accept & Proceed</button>
      </div>
    `;
    modal.onclick = null; // Disable close on backdrop click
  } else {
    if (closeBtn) closeBtn.style.display = "block";
    footer.innerHTML = `
      <button type="button" class="btn btn-secondary" onclick="closePrivacyPolicyModal()">Close</button>
    `;
    modal.onclick = (e) => {
      if (e.target === modal) closePrivacyPolicyModal();
    };
  }

  modal.classList.add("show");
}

function closePrivacyPolicyModal() {
  const modal = document.getElementById("policyModal");
  if (modal) {
    modal.classList.remove("show");
  }
}

function acceptPrivacyPolicy() {
  const role = db.getCurrentRole();
  localStorage.setItem("harita_policy_accepted_" + role, "true");
  closePrivacyPolicyModal();
}

