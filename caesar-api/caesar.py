"""
Caesar Cipher - Pure Python Implementation
Kripto Simulator 2026 | Josua Reynold Tampubolon
Data & Transform Specialist
"""

# ── NATO Phonetic Alphabet ──────────────────────────────
SPELLING_ALPHABET = {
    'A': 'Alfa',    'B': 'Bravo',   'C': 'Charlie', 'D': 'Delta',
    'E': 'Echo',    'F': 'Foxtrot', 'G': 'Golf',    'H': 'Hotel',
    'I': 'India',   'J': 'Juliet',  'K': 'Kilo',    'L': 'Lima',
    'M': 'Mike',    'N': 'November','O': 'Oscar',   'P': 'Papa',
    'Q': 'Quebec',  'R': 'Romeo',   'S': 'Sierra',  'T': 'Tango',
    'U': 'Uniform', 'V': 'Victor',  'W': 'Whiskey', 'X': 'X-ray',
    'Y': 'Yankee',  'Z': 'Zulu',
    '0': 'Zero',    '1': 'One',     '2': 'Two',     '3': 'Three',
    '4': 'Four',    '5': 'Five',    '6': 'Six',     '7': 'Seven',
    '8': 'Eight',   '9': 'Nine',
}


def encrypt(text: str, shift: int) -> str:
    """
    Enkripsi teks menggunakan Caesar Cipher.
    Rumus: E(x) = (x + shift) mod 26

    Args:
        text  : Teks yang akan dienkripsi
        shift : Jumlah pergeseran huruf (berapa posisi digeser)

    Returns:
        Ciphertext hasil enkripsi

    Contoh:
        encrypt("Hello", 3) -> "Khoor"
        encrypt("Hello, World!", 3) -> "Khoor, Zruog!"
    """
    result = []
    shift = shift % 26  # normalisasi shift (misal shift=29 = shift=3)

    for char in text:
        if char.isalpha():
            # Tentukan titik awal: 'A' untuk huruf besar, 'a' untuk kecil
            base = ord('A') if char.isupper() else ord('a')
            # Rumus: E(x) = (x + shift) mod 26
            shifted = (ord(char) - base + shift) % 26
            result.append(chr(base + shifted))
        else:
            # Karakter non-huruf (spasi, tanda baca, emoji) dibiarkan
            result.append(char)

    return ''.join(result)


def decrypt(text: str, shift: int) -> str:
    """
    Dekripsi teks menggunakan Caesar Cipher.
    Rumus: D(x) = (x - shift) mod 26

    Args:
        text  : Ciphertext yang akan didekripsi
        shift : Jumlah pergeseran yang digunakan saat enkripsi

    Returns:
        Plaintext hasil dekripsi

    Contoh:
        decrypt("Khoor", 3) -> "Hello"
        decrypt("Khoor, Zruog!", 3) -> "Hello, World!"
    """
    # Dekripsi = enkripsi dengan shift negatif (kebalikan arah)
    return encrypt(text, -shift)


def spelling_alphabet(text: str) -> str:
    """
    Konversi teks ke NATO Phonetic Alphabet untuk keperluan edukasi.

    Args:
        text : Teks yang akan dikonversi (huruf & angka)

    Returns:
        String NATO phonetic, dipisah spasi

    Contoh:
        spelling_alphabet("HELLO") -> "Hotel Echo Lima Lima Oscar"
        spelling_alphabet("Hi 5")  -> "Hotel India Five"
    """
    words = []
    for char in text.upper():
        if char in SPELLING_ALPHABET:
            words.append(SPELLING_ALPHABET[char])
        elif char == ' ':
            continue  # spasi dilewati
        # karakter lain (tanda baca, emoji) dilewati
    return ' '.join(words)


# ── Caesar Cipher alias (kompatibel dengan nama fungsi lama Paulus) ──
def caesar_encrypt(plaintext: str, shift: int = 3) -> str:
    """Alias untuk encrypt() - kompatibel dengan main.py Paulus."""
    return encrypt(plaintext, shift)


def caesar_decrypt(ciphertext: str, shift: int = 3) -> str:
    """Alias untuk decrypt() - kompatibel dengan main.py Paulus."""
    return decrypt(ciphertext, shift)


# ── Quick test saat file dijalankan langsung ─────────────────────────
if __name__ == "__main__":
    print("=== Test Caesar Cipher ===")
    plain = "Hello, World!"
    shifted = 3

    cipher = encrypt(plain, shifted)
    decoded = decrypt(cipher, shifted)
    spell = spelling_alphabet(plain)

    print(f"Plaintext  : {plain}")
    print(f"Shift      : {shifted}")
    print(f"Ciphertext : {cipher}")
    print(f"Decrypted  : {decoded}")
    print(f"Spelling   : {spell}")
    print(f"Match      : {plain == decoded}")