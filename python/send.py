import os
import re
import pywhatkit
from datetime import datetime

# Message content
CAPTION = """
Terima kasih telah melakukan registrasi untuk acara Customer Business Forum Tahun 2024
"Bridging Challenges, Strengthening Bonds"

Yang akan dilaksanakan pada :
Hari/Tanggal 	: Rabu/16 Oktober 2024
Waktu		: 08.00 - 13.00
Tempat		: ICE BSD - Nusantara Hall Ground Floor
Maps		: https://maps.app.goo.gl/BdRhjvCP8wufZd4PA
Dress Code : 
* Pria - Kemeja batik lengan panjang
* Wanita - Nuansa batik

Harap menyimpan dan menunjukkan QR code diatas pada saat registrasi ulang di venue

*****

Undangan ini berlaku untuk 1 (satu) orang

Download aplikasi My Pertamina melalui tautan berikut :
Google Play Store - 
https://play.google.com/store/apps/details?id=com.dafturn.mypertamina&pcampaignid=web_share

Apple Store -
https://apps.apple.com/id/app/mypertamina/id1295039064

Sampai jumpa di Customer Business Forum Tahun 2024

Regards,
Management
PT Perusahaan Gas Negara Tbk.
"""

# Folder containing images
IMAGE_FOLDER = './images'

# Log file
LOG_FILE = 'message_log.txt'

# Regular expression to extract the phone number from the image filename
regex = re.compile(r'^[\w-]+-(\d+)-[\w-]+\.png$')

# Function to convert local Indonesian number to international format
def convert_to_international(phone_number):
    # Check if the phone number starts with '0' and only contains digits
    if phone_number.startswith('0') and phone_number.isdigit():
        # Replace the leading '0' with '+62'
        international_number = '+62' + phone_number[1:]
        return international_number
    else:
        # Return None or raise an error for invalid numbers
        return None

# Function to send an image and caption via WhatsApp and log the result
def send_image(phone_number, image_path, caption):
    try:
        # pywhatkit uses 24-hour format for time; calculate 1 minute from current time
        now = datetime.now()

        # Send the image via WhatsApp
        pywhatkit.sendwhats_image(f"+{phone_number}", image_path, caption, tab_close=True, close_time=15)
        # pywhatkit.sendwhatmsg_instantly(f"+{phone_number}", 'Hello Test', tab_close=False, close_time=5)

        # Log success to a text file
        with open(LOG_FILE, 'a') as log:
            log.write(f"{datetime.now()}: Successfully sent message to {phone_number}\n")
        print(f"Message sent to {phone_number}")
        
         # Rename the image by prepending '(DONE)'
        new_image_name = f"(DONE) {os.path.basename(image_path)}"
        new_image_path = os.path.join(IMAGE_FOLDER, new_image_name)
        os.rename(image_path, new_image_path)
        print(f"Renamed image to: {new_image_name}")
        
    except Exception as e:
        # Log failure to a text file
        with open(LOG_FILE, 'a') as log:
            log.write(f"{datetime.now()}: Failed to send message to {phone_number}. Error: {e}\n")
        print(f"Failed to send message to {phone_number}. Error: {e}")
            
# Loop through the folder and process each image
for image_file in os.listdir(IMAGE_FOLDER):
    if image_file.endswith('.png'):
        try:
            # Split the filename based on '-' and extract the phone number
            parts = image_file.split('-')
            if len(parts) == 3:
                name = parts[0]
                phone_number = parts[1]  # Extract the phone number part
                email = parts[2].split('.')[0]  # Extract email (remove .png)

                # Check if phone number looks valid (basic check: should start with 0 and be digits)
                if phone_number.startswith('0') and phone_number.isdigit():
                    # Convert local Indonesian number to international format
                    international_phone_number = '+62' + phone_number[1:]
                    image_path = os.path.join(IMAGE_FOLDER, image_file)
                    
                    # Send the image and message
                    send_image(international_phone_number, image_path, CAPTION)
                else:
                    print(f"Skipping file {image_file} - Invalid phone number format.")
                    
                    with open(LOG_FILE, 'a') as log:
                        log.write(f"{datetime.now()}: Skipping file {image_file} - Invalid phone number format.\n")
            else:
                print(f"Skipping file {image_file} - does not have exactly 3 parts.")
                
                with open(LOG_FILE, 'a') as log:
                    log.write(f"{datetime.now()}: Skipping file {image_file} - does not have exactly 3 parts.\n")
        except Exception as e:
            print(f"Error processing file {image_file}: {e}")
            
            with open(LOG_FILE, 'a') as log:
                log.write(f"{datetime.now()}: Error processing file {image_file}: {e}\n")
