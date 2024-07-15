<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('passwords')->truncate();

        $arrayPass = [
            "JWxUIpeD",
            "7QCx6rOj",
            "TaS2zJen",
            "cjcZHypG",
            "ZpZ5mhyP",
            "TV3KsBN9",
            "adNm0iXY",
            "KlzF35sI",
            "Qx6RkEJK",
            "Fx8dZ9WM",
            "jB5sgfXk",
            "AQijJVHT",
            "xeEyYYex",
            "JlkOHhaw",
            "2fedmX8y",
            "U9xlp6zE",
            "Oqu5WnxD",
            "xmWUKYuV",
            "3BDc2HVg",
            "UqB9OEuv",
            "Hd98Kh9N",
            "ILj39QbX",
            "CPCweF0O",
            "MvnpEirn",
            "Bwr04Oyl",
            "bfwXXuGd",
            "mvBZxNnC",
            "PizmxmL5",
            "OQtzEw8k",
            "j8fr6lPJ",
            "pw7Ycwoi",
            "1C0zNlHy",
            "jRg23S61",
            "2ZNydLqt",
            "GbyL9x1O",
            "zbC2FTeA",
            "WbdDz1uw",
            "EWymlteZ",
            "Wp5T5NUk",
            "4nbYLwIZ",
            "viTS7Xpw",
            "cPsr9Hvw",
            "kORXjIxS",
            "49U9i2s1",
            "fwKbMCQr",
            "4Otqs1KL",
            "F2qyzn2q",
            "CbxTMeu3",
            "VVzo7yec",
            "MsFlTVZz",
            "gV1oDQQY",
            "IG6DkmmS",
            "s7dmYYIz",
            "dYqJI0ru",
            "MuCW1eOA",
            "3YrEPyvK",
            "TDxFoCkK",
            "zzIQVHsH",
            "xMOH7fqd",
            "DWpbJEks",
            "kID7bnPz",
            "upmBlV0S",
            "EYp40pFa",
            "kwC5eg9D",
            "tXfuPFpA",
            "WfHji2le",
            "3xnoTMZJ",
            "YZtx2bqS",
            "2QEPETVg",
            "5eNdESze",
            "mxnKATka",
            "m0boKB7u",
            "t10g00hw",
            "DcG80I09",
            "eUtTWX4B",
            "IXWlczkn",
            "8cId6CyS",
            "XxoVVzmO",
            "tTdnNEfO",
            "vyKwW2r3",
            "PC9IfF8d",
            "nvfC1nqV",
            "r71sDs98",
            "UAqiZXBm",
            "wlsGjTXG",
            "gVW4TKvg",
            "6nxdVeFb",
            "qNfIdb8H",
            "4ef5FxEk",
            "NHjwqHSh",
            "KGbimu0l",
            "TQ2QnIgW",
            "4B3ZXAhX",
            "nsrzPwvt",
            "MymRJUZZ",
            "Euda96v9",
            "UVei2dJY",
            "jgDF5eH4",
            "jeglYswO",
            "Jh9mFiko",
            "E2AYb7nx",
            "KCfithxN",
            "LnQZgIUL",
            "8IpPcz2H",
            "AEx6PAHp",
            "v9STZEGn",
            "KlHiARmt",
            "oRpgLNIg",
            "pTzuQrTk",
            "58R3q9uT",
            "3y86VSza",
            "h4ii3a1o",
            "vAZIs3Xt",
            "VkEBG75x",
            "LciSbMUr",
            "VqBJyHZs",
            "KZg0PoB4",
            "r54pHc0C",
            "PFHUunjW",
            "LDl96PZJ",
            "i4SoQVfC",
            "RNUvDp1e",
            "WfZWMUEm",
            "cgUcBvCe",
            "mW7esi19",
            "y4Cwl9sm",
            "f4ze6BiJ",
            "qjUBPsg5",
            "0p4XkY2B",
            "IEgiKuhn",
            "cFJyQKta",
            "M3Hl3uMz",
            "kCIzvCAV",
            "ejgwMUlr",
            "So4vtrS1",
            "cUjB657W",
            "Wo6iSJd1",
            "SdHqEfwh",
            "nseGVv5W",
            "WCUrH5xG",
            "zGpphSbf",
            "CpsG3eNB",
            "h3aFX0DY",
            "ZXvudPED",
            "uuD0traj",
            "1PXKzpSp",
            "IDB4K8GX",
            "fjEGKFBs",
            "VWVB9epA",
            "r2NCY3kI",
            "4ttsnqph",
            "EA1QTwW2",
            "jN6XBINa",
            "q9HH0olu",
            "afErlR1r",
            "Y9K14WYj",
            "G9qP081p",
            "HNm0xozM",
            "rRqZvkvA",
            "1JvDShWN",
            "4Pnodlod",
            "HhD0a8dH",
            "w6xUvOZH",
            "lNwh5ra0",
            "hqRhOcKR",
            "Y4ZDuaGd",
            "w1sL82sZ",
            "tryfzckc",
            "HYq11ut8",
            "5WHoYwse",
            "Vaatrhue",
            "dvmb0obe",
            "SysDScPq",
            "MrK3eCDE",
            "AU070dEb",
            "bdKRHM9C",
            "WT1yB8WO",
            "RPH1xYhu",
            "mX0U1GeR",
            "ACRFYdfN",
            "4oLoq5io",
            "Utqpd4et",
            "EHOUvLat",
            "4Vx2Ger5",
            "A8YSGkOH",
            "yurrVGSj",
            "HOhGcQZs",
            "1x5m7azx",
            "v1VK3b2f",
            "xvSxSh5b",
            "qwmbR8uP",
            "jgRPWhIT",
            "xtdYQrp3",
            "4aMBIsH0",
            "CxO8GHHb",
            "RYupMksw",
            "E9naMNZt",
            "mEoeTrvC",
            "Gk8OFjJC",
            "PCYTMCfI",
            "KhM9sPDB",
            "5hmilTOJ",
            "nRYhYwzC",
            "Cs8MWIyn",
            "AU5GaZjK",
            "qTjO7P8d",
            "pQGtydFT",
            "TaUriUXX",
            "tjapqZNX",
            "zkfroNAP",
            "kaCOk8ag",
            "M90uh4qi",
            "54m10Si3",
            "ubxskCYF",
            "zwm04cQ8",
            "bHr4DDM0",
            "hkIVLq8Y",
            "3xAwXuca",
            "VJGCWFu1",
            "2oRj0qpq",
            "1UeNbDH4",
            "UzrmliLK",
            "4iwhSNH7",
            "QS5bNYTG",
            "uVND9T4R",
            "s48aZEbQ",
            "AkA1DhUI",
            "v7AGGXYV",
            "hm0Bkf30",
            "zT64KcSr",
            "xv0h0GCA",
            "bta8XwdX",
            "cWmbIOnR",
            "HT5w3yZK",
            "1eiPs0pY",
            "TwIgAgqj",
            "7nkmqEQH",
            "zcG6fufv",
            "78tt7Ysa",
            "bUKy1rZ7",
            "0F6weBg1",
            "yagMQ0l4",
            "D5wHtagn",
            "gYKQrgdB",
            "0YzEHIAZ",
            "pxz7foUZ",
            "zbzBV6Fa",
            "PEHYyoEY",
            "21vHHwzg",
            "qbPBNxO8",
            "J54NQ3Nv",
            "q3pUZZjC",
            "aRugfk7w",
            "PRZ1OpAa",
            "1AZ65gZb",
            "ertW0YS0",
            "ahqo8bpr",
            "smJeSbCI",
            "YTHpGI9e",
            "U9FZakdr",
            "ogYt7rOi",
            "D82sSomU",
            "y00SHXK0",
            "JvIB9XNB",
            "Si0mM2ZD",
            "SJm98wWW",
            "imjCFgs1",
            "BewiwUSK",
            "mNGXsJb9",
            "ZZmtbWiL",
            "nlptD6a2",
            "k16FniwJ",
            "Az5YB5Km",
            "z24Z9lMk",
            "RYuFWKAW",
            "tJNvWsJk",
            "xnHYbVbh",
            "mkf9ZLa9",
            "snZfEdHV",
            "g3cDPVlI",
            "h8yujK6V",
            "FyUUFxTW",
            "uv4eV9Ch",
            "clt5Jl7j",
            "ntiKuaS8",
            "E8Gx5UVM",
            "uB2FiICv",
            "J00Vq3RL",
            "Pqv2gfzH",
            "WrHLHLAC",
            "YmzMDsvC",
            "aLMDmTwJ",
            "UtR8YKM9",
            "94XABUcQ",
            "exIWs1IW",
            "HsO9ufQX",
            "Cdfypd7m",
            "wRxUXSZu",
            "TIy0buVU",
            "OscvRMXl",
            "yNQrpoxV",
            "44DyDHFQ",
            "1JuQzBL8",
            "x4kUlZ9m",
            "iSzItMCT",
            "9JtPS8B0",
            "RrrkNF2G",
            "7xoxi5yl",
            "4RrjJZAi",
            "APJJnvCp",
            "AsbdOTC4",
            "pEksNsfg",
            "wvXWvhk3",
            "5h8M5h4l",
            "t5FSFCAC",
            "tYk63GpQ",
            "A6KjY5R8",
            "qHsJkL0D",
            "dtrNFap2",
            "fUa7EXGj",
            "ic4MNuSF",
            "09XIka7T",
            "IFklke2M",
            "TWZqcf4g",
            "Qs5vdGFz",
            "N2Y2Ft8n",
            "bk5JInS6",
            "nUMbazPW",
            "92J17sYE",
            "ass0B5kA",
            "T12Vj37R",
            "VkC6zrrq",
            "dCwDEgv0",
            "NURFYddF",
            "GyYa4N03",
            "HX8J2G3t",
            "CgysH99L",
            "FZyhP61Q",
            "5iDorQkf",
            "BDPKiYVH",
            "kPPKx2ez",
            "3iET07oX",
            "g10GkgI1",
            "4EQX1cfi",
            "qRLHAMOD",
            "46BrB8mw",
            "D0P8YPIW",
            "bsS1XEe2",
            "n5J1f1nb",
            "69b4PJRS",
            "7Lv2k9sk",
            "0p41WoXS",
            "3odCHSMv",
            "oeEcbUe5",
            "QnjBkO3h",
            "ysSOWCWQ",
            "AxeGZPLq",
            "t3drUvyp",
            "kjy2U0GP",
            "IjnlWPCt",
            "TiHeXI62",
            "TMg88Fg9",
            "nQvKTSUN",
            "oUePwzQw",
            "VJiMrw9A",
            "r0JWFWU1",
            "PIMrJAor",
            "w42FYHbV",
            "qqcRhevu",
            "DPnCY6Hw",
            "fv9adH8L",
            "mWYhpEE8",
            "LujwccHZ",
            "qAuHi48U",
            "spc0ZvO3",
            "IOfoNNtN",
            "uCwmvxGp",
            "CjgamgkR",
            "6PXJwqg5",
            "ClrrBSYe",
            "spsv51BZ",
            "tVwD0QwU",
            "Svq2CZrI",
            "vNkJNSkH",
            "EVbk8Jnx",
            "kgu90Kpq",
            "mPHDOBn7",
            "LnYkUQ6J",
            "ptZMffFE",
            "EsnB0viv",
            "LoQbnOBX",
            "tcyXcSaG",
            "DoCz6Yhn",
            "WYTYJFpp",
            "ee0HIRKg",
            "7rONnrxZ",
            "JVyFXTTw",
            "uqe2X5Bg",
            "NYo9XoMh",
            "simbTZKD",
            "hXIg21Fu",
            "pCqvhL9h",
            "7kq4kv8i",
            "U94KK61y",
            "esayv8WB",
            "rJC1qjLB",
            "iHnfgTkq",
            "bjq9opZ9",
            "7VAhGRgp",
            "BiwuSu2J",
            "LAK2jVew",
            "XAOvcac3",
            "A6ZYfsWX",
            "WcKGlp7m",
            "1P87manW",
            "SiqlkJgR",
            "pAQYIm1P",
            "MJOM0Hcg",
            "zT2UySz6",
            "7ssjgXdz",
            "RNLR6hxt",
            "wGP39i1Y",
            "WaEeB4VH",
            "qvdHqWxN",
            "oQTBhTAb",
            "5lQnuz6y",
            "3fp3B0dj",
            "LQTDzsyy",
            "Db8AhZvt",
            "5IaDJbnU",
            "tUk2emFt",
            "ZHXG7nPM",
            "fYh59APs",
            "DQDAAzop",
            "reG5kGFm",
            "Tnzevocx",
            "WS6lozjL",
            "KEjvEfpl",
            "BfmbeZ6d",
            "WLOGg1Wy",
            "iRCiT6vU",
            "DdduSG50",
            "sxmwGxAR",
            "qsmZ65hv",
            "CeCU3HnK",
            "Qt8eAovP",
            "cb9sIGEa",
            "TW0wVSjL",
            "tQG809n6",
            "ucIlloWJ",
            "gnaKVFjY",
            "NYQYGTOs",
            "uudXv52d",
            "UXuRBdt0",
            "NOIVF3qL",
            "qgREVSlU",
            "nJZNM9xw",
            "V2htYsmD",
            "hBDMFt6C",
            "CTDTBvoc",
            "m5ZcsVmK",
            "bZMOlFba",
            "LbXvPRgL",
            "oysRsvSa",
            "pZZo4y6M",
            "AmerO1rE",
            "e8cbTmKB",
            "F5MO8jsV",
            "51JL2unZ",
            "vpLVdKZE",
            "aFk1fXry",
            "Tq7Awa5y",
            "c1bPCRkJ",
            "SfJvEOWC",
            "yf8tgiqo",
            "TNkgKrHg",
            "okozPJMw",
            "uFxtpTOk",
            "P8RhRnGQ",
            "OIEHdY3q",
            "aiiKROIP",
            "jy8mEmJa",
            "LTePcqhh",
            "GkYEbCMJ",
            "H6nsJcbZ",
            "kxrqM771",
            "AY3JKWJq",
            "5akCnMsg",
            "4ffNZiLd",
            "48Jz40Ev",
            "B5CqbnDj",
            "fjR256Pa",
            "F0r76bVU",
            "YKI4u3TT",
            "cMav0jHH",
            "r4BJPEI6",
            "Sqb2hrQY",
            "lywWbsvz",
            "G8ntFl4b",
            "kqHuoHk0",
            "yGoSUv5x",
            "RWHr7vRL",
            "LCWAfiCk",
            "9bIs23UV",
            "pNEG948S",
            "EWmc20qn",
            "C1lFLLYt",
            "3eHcJcDi",
            "t0RbAKxt",
            "vRK80Sw9",
            "DKoJE3ql",
            "I8vrrXlu",
            "Ab1AzvGn",
            "XTV4KV9v",
            "OWtOJOiX",
            "H0qCgMPP",
            "mfRcAcvV",
            "IcIpfMA6",
            "3LxD6prp",
            "Ve2HhtJo",
            "UF8QkqHF",
            "YQ2ODao0",
            "Be91hoiQ",
            "uhJKKuk4",
            "THbfI0By",
            "mlaMqqVj",
            "1oUhwRT5",
            "C0VnelZo",
            "HXHunrWr",
            "jteTvIvo",
            "hWgHHEMH",
            "7T8R8LPQ",
            "fNO04sIJ",
            "4xY4nMLf",
            "TXlxX12C",
            "vrgUvqIS",
            "v4PYpOou",
            "AaLYk1YY",
            "YV90gBlA",
            "qZOaQeZ1",
            "eGbRtClx",
            "ii2QM5eH",
            "QDqSAOdn",
            "N99giaNu",
            "eu1Dm1SS",
            "5SeqrxHG",
            "awf470dl",
            "36YBIwtg",
            "VLDiBQ5G",
            "uRrr3Yyd",
            "w6VWCCWR",
            "2QEbXY1W",
            "UerUrpXL",
            "At12bMPQ",
            "zLCd0Y87",
            "r6PbXqGY",
            "xa8YNLEA",
            "KYwHqfHK",
            "Fvu1JgDz",
            "HrvICdfI",
            "u2UP0LOo",
            "b6ACjaRq",
            "5KUG4eNK",
            "mYyvtnxv",
            "2C09vflt",
            "ExxG2Xu2",
            "muaVI8UH",
            "Trqhd5C3",
            "cUy3XwJ9",
            "Ivey4qeV",
            "pIsHAEU0",
            "IptMtYBn",
            "4SCp7Vkd",
            "Vlu640v4",
            "Koq2MHKM",
            "GMUO4MSh",
            "GHsfLLla",
            "CEOo8W8c",
            "MFS7ou1o",
            "T5D6aYMy",
            "8xhENsJE",
            "4Ogw1b2k",
            "kwupZUel",
            "hdB9Ucad",
            "iwnj8RFe",
            "Yq1NYWoj",
            "gUZVH7zc",
            "DtCsvLF2",
            "T0rXNcQr",
            "WYe1FSbd",
            "JZXWlz55",
            "aGV5l8kn",
            "wV7yuNpF",
            "Ep1hSSsK",
            "tb0W3Aar",
            "Mgeib6ZT",
            "DfSBHbIm",
            "9sTIyVRx",
            "S8ngbE3A",
            "IT6KHNLY",
            "5Z6jdtG8",
            "Qn5wXT76",
            "zx3YMhUs",
            "t9ztRNce",
            "l3cQuvs9",
            "0rEBJx9u",
            "il5AiOH6",
            "VVt4TbRi",
            "rnG9TIrb",
            "iO2xKynI",
            "Bj0Rvewz",
            "DlfvcljK",
            "BUkL7sz7",
            "vi3bgLNj",
            "71xrsriu",
            "jTvTmEYa",
            "YPN0jorA",
            "qM6F5ayI",
            "HqZIjfci",
            "uc7K4slD",
            "Vv4VCTIx",
            "Gb7qtb4k",
            "DejEuHwV",
            "aem032bT",
            "9xx192Id",
            "aUbFAETf",
            "NivCst4J",
            "rCdqVHZl",
            "ag8tKWmh",
            "hd80hWHr",
            "UTPkl3Lc",
            "tMYfcJg9",
            "rzPekrRN",
            "kjVWMOTc",
            "9b1UxKf3",
            "2ACKJpdg",
            "rGsZn0o4",
            "uiQfczsj",
            "0K1J5uRD",
            "zNCOWJPI",
            "Jxb1yTcZ",
            "hhjPjLiE",
            "Jxd9FPc0",
            "uDLODEYX",
            "cefIqlLG",
            "Sct4Qk2v",
            "YPdU1Bhs",
            "13aq46Rb",
            "55gvTnbY",
            "gMTZTVu6",
            "KMbaW6Az",
            "2naxMHIT",
            "4Drxz2jL",
            "nkxS0IFg",
            "11xqEOIV",
            "TNymDD7Y",
            "lmwOPtZQ",
            "NZ3wrpLh",
            "6DKykS1x",
            "Lm6kofyz",
            "uTljzWlQ",
            "qkxi8vGx",
            "hs3eD1cL",
            "zvwJxpl1",
            "p6bCOehN",
            "T8jEB5e6",
            "qRzFIusH",
            "uU80JCYO",
            "xOcdzcAb",
            "Y4VXq4kA",
            "tKCQmI7X",
            "lrxNLURn",
            "4fXGeZby",
            "nH1xTwKf",
            "WdZVK5wr",
            "3uQ0Qz4F",
            "Cof3qD20",
            "Zldfvy2d",
            "g4UDHj9Z",
            "gwpsCTLx",
            "YlGSLCsR",
            "qP6HOsk3",
            "dIMO2lxh",
            "raQEpROY",
            "dCW9K67r",
            "pFyPD2HD",
            "61MxHgvX",
            "1m1JXPb6",
            "icBcAMfB",
            "FwheCjEB",
            "f2m1pyJ0",
            "PXJLS3FM",
            "AijMPc0k",
            "KB9KHHD8",
            "FBGLI9lm",
            "GlYfXnhm",
            "IhkyaswR",
            "sopycTdU",
            "i9W7NCCo",
            "7qI3ck5z",
            "czBAfb2K",
            "loy5YfDa",
            "fcEzoCrD",
            "F9VoyyBU",
            "oEwPNvCg",
            "b6xkjcN8",
            "Ywtq0MUj",
            "IFf5Mfqv",
            "um2qhVLC",
            "9UrjE6T7",
            "odf9WUKU",
            "r0PvcTCS",
            "TrhzimDN",
            "Rdg8h3dt",
            "j5BuPCkp",
            "eVqOAaqS",
            "dHUIjMGI",
            "ZB2Z7hMh",
            "PqMGWulm",
            "TWsSnH8E",
            "w77dqBWX",
            "uyuMkcel",
            "rh4JKP8S",
            "4AtoApr0",
            "wSJyh03R",
            "GDcqihna",
            "QChFMWYe",
            "jXIzRYCk",
            "N7L48cnP",
            "DouZeR0G",
            "WajW7GI7",
            "hkcZRRVJ",
            "xfgsoSv8",
            "kGmgbQG2",
            "mSkBdcgP",
            "c7PC8YOu",
            "crfgv41e",
            "Aj3QbPO7",
            "WQfc30p2",
            "Q6tKggxU",
            "BqT58EV6",
            "q0mKTC4D",
            "LqyLyCl0",
            "GEQPoBEf",
            "RTq1rXU0",
            "GdWyUVSI",
            "0zh89LMK",
            "IBkBGfHX",
            "VnuuYiIk",
            "w9Um9Xg3",
            "uoLqLStX",
            "qdByXyE4",
            "GAhaCUiA",
            "Ha6IhNkk",
            "DBJ3dwyw",
            "8gOWr4Us",
            "UWmnfIO9",
            "Q9KvmgHn",
            "Y03Ew9H8",
            "Jx20CM7y",
            "o5m8971l",
            "wtxN8p00",
            "7TWv06W1",
            "HjylkQNm",
            "Qu52b139",
            "rsn64jHg",
            "PLniWPNS",
            "4u5O5z0G",
            "Dzn7sfL9",
            "1WwwqIUf",
            "fdnf29aK",
            "7yPswnd5",
            "fiovZNOY",
            "WgZh4YAa",
            "b6HvcsW6",
            "F7cQhaiC",
            "QfpsCUGF",
            "P0uSdRCR",
            "WO4tUiPB",
            "s6vOBIjK",
            "NZ9mR4Yo",
            "EaYAIe50",
            "BhSRSXQF",
            "pIVnj31A",
            "jl0pEF7f",
            "zIkr5cBv",
            "7CbUlihm",
            "oEo6mzVu",
            "PPnAUq3Q",
            "7NVVV71a",
            "4jmVefYz",
            "lRVtH9x8",
            "Y8TYElJ2",
            "plwqE1fb",
            "nefX7v7e",
            "20yztQS5",
            "4nu3VpZf",
            "EKJI5ohd",
            "vXFECjfl",
            "lBNhSCDJ",
            "KLZRQHjs",
            "ybGshHES",
            "04haZLdb",
            "anu6DU6z",
            "sg4wY59k",
            "F4jc0QSO",
            "OXhofw2Q",
            "yMxiXZl6",
            "qpgO3gAO",
            "40KQvp4b",
            "7TXk3Vqn",
            "2iwyfdNH",
            "hpS6N9ya",
            "48jtFYg1",
            "mUPoaDzh",
            "KJ5jdqAY",
            "rhMhjc7k",
            "M8m09fhO",
            "Pw5edlnS",
            "YJU9pfZy",
            "0L2nI8gx",
            "UPUNUUww",
            "rcRwUBJO",
            "LaiicaGX",
            "IPyZUnOd",
            "BkOzDMmu",
            "AWtsSBsH",
            "ZiDPECbp",
            "vQwTliXr",
            "0We3CSqT",
            "WkqNvMsX",
            "YkeMc7HA",
            "BocpPZme",
            "xUfYM3Rg",
            "q4G0BYJU",
            "T5Sfo8KT",
            "6V1wm1BV",
            "9PxBClIs",
            "ZKMKbke7",
            "iRm3wmlz",
            "2Pb7pGGd",
            "QSq7Me3J",
            "YWVuxEDT",
            "2Mhd88pI",
            "lAUQYEvz",
            "VNgMD3Yb",
            "99dJAi74",
            "hfXTv3w0",
            "mFXsHdwh",
            "ElzQPuBw",
            "jNey8rAt",
            "3ou9tO6O",
            "gaYmY3M7",
            "wVIRAqJ5",
            "W8fA8Ut5",
            "tG3WyHxa",
            "4g60GLsg",
            "CFjSVvwq",
            "TUOBQGGu",
            "ih89nQSd",
            "ZspdcWZL",
            "MoDH0kEo",
            "tWb0qZ26",
            "oZiKhuZV",
            "O06kZgwv",
            "dBmIzsWL",
            "XZuMHO6f",
            "hrFcSKas",
            "Si1hzcM0",
            "twX9O4ha",
            "RwHzbV7c",
            "U8OW5uhP",
            "lbb4ncrm",
            "RG7HnVmx",
            "vQdJIxa9",
            "sBpdve7O",
            "ZfzAcR8U",
            "K49A0C76",
            "igasVbvO",
            "czWEOCi3",
            "ablroLaT",
            "E6Z55fVk",
            "4Dmk4bps",
            "L3EAzfHY",
            "oC1iTwyV",
            "YEldA3oU",
            "Eh2dWj3F",
            "HcbNH6DW",
            "ZUsLoaSa",
            "3gWD0Eps",
            "TLeGLaPL",
            "1Z3b4oTS",
            "ZBcU8pnY",
            "2cMzJiri",
            "Ydenpa6C",
            "ISszko32",
            "UJyUJT3t",
            "ebTK2uMF",
            "xeP0g3rc",
            "RZMz7cji",
            "pOchjO5r",
            "OTWesvum",
            "wp3cV22G",
            "kVwoWEVy",
            "MicXvqCP",
            "AqSvMQam",
            "7UgsK0lt",
            "IPmefYDh",
            "sGerOaz9",
            "MojgjB6d",
            "cd3IJHg1",
            "vS7XfK96",
            "bclGyJDM",
            "2WdO1QxG",
            "qLpMk7Nt",
            "dbpZ2mQc",
            "MDswHpnB",
            "nxxUCE9H",
            "74JzctmA",
            "8oazpshE",
            "fApUnpaA",
            "IqjA6MfN",
            "Ty5X1cbV",
            "z7AVz8iA",
            "cPdBJ8Ui",
            "HGsSstwc",
            "MDJapigU",
            "JZxe6exd",
            "7uaznShM",
            "3gn5bedK",
            "D0pPMIkZ",
            "YnIzqCee",
            "K4xdfeDJ",
            "EkEa6V2n",
            "1PBi37a8",
            "wyfzAwdr",
            "0wSids4e",
            "Tw6d68zG",
            "2bFIAVV1",
            "FCuJo1FA",
            "g2cl0S71",
            "Qo2raVhO",
            "HHnPQH1W",
            "bpdXlV8H",
            "gQB4IP4u",
            "NoQK2lTB",
            "Nos0gTy4",
            "B79D2xBA",
            "OLLPeUnc",
            "FHGFJSvQ",
            "lowAmmZ0",
            "V2WOkeSp",
            "yu3oAauU",
            "3nj5w8sH",
            "b1gM9mGF",
            "kuasZNvN",
            "1X5INjSd",
            "J5UST0TE",
            "sBI5BG5o",
            "VTFpOdy9",
            "yqaVIOKs",
            "SFK6Iq91",
            "nxg1i4zA",
            "xpe9gN0G",
            "I6DY1qaW",
            "h08bOfr4",
            "SscNsBw5",
            "wMk2gPBL",
            "uJjAlZ4q",
            "YnbsYILk",
            "fZL0dYxp",
            "GA43ncCK",
            "EPJWjOqO",
            "E4GNPQWm",
            "H9XptDa6",
            "8Xi7NpzD",
            "ZHis0QnG",
            "xu2TBiZb",
            "ekhP45qk",
            "Qw4DMx4m",
            "hrQtPXPm",
            "duKiXsad",
            "RhdVJP6z",
            "Y0mc19Fr",
            "73FNocfn",
            "mFsXypan",
            "EfGomh65",
            "dKuZ4vys",
            "8w5SbrXs",
            "1mgUadFQ",
            "VltYIjBg",
            "14MOVsVO",
            "ru84JkKQ",
            "pu1eLyDM",
            "5pC8F1mS",
            "eKcZ9TAq",
            "V7wjVBsc",
            "BEFKlIz6",
            "N2j9enQm",
            "fp59wzex",
        ];

        foreach ($arrayPass as $pass) {
            DB::table('passwords')->insert([
                'password' => $pass
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
