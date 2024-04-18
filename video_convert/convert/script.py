import os
import json
import uuid
import shutil
import subprocess
import urllib.request

#-----------------------------------------------------------------------------------------------------------
# Phase 1:
#  1 - Vérifie si un script n'est pas déjà lancé & qu'il n'y a pas rien dans la liste d'attente
#  2 - Récupère le premier élément de la liste d'attente et précise dans "waiting_list.json" et précise "in_progress" à 1
#  3 - Créer les dossiers nécessaire si n'existe pas

file = "film.mkv"

with open(f'waiting_list.json', 'r') as f:
    waiting_list = json.load(f)

#-P1.1
if waiting_list["in_progress"] != 1:
    if waiting_list["wait"] != []:
#-P1.2
        now = waiting_list["wait"].pop(0)

        print(now)

        json_list = {'in_progress':1,'wait':waiting_list["wait"],'current':now}
        with open(f'waiting_list.json', 'w+') as outfile:
            json.dump(json_list, outfile, indent = 4)
#-P1.3
        if not os.path.exists("./convert/assets"): 
            os.makedirs("./convert/assets") 
        if not os.path.exists("./convert/output"): 
            os.makedirs("./convert/output")
        if not os.path.exists("./convert/output/video"): 
            os.makedirs("./convert/output/video") 
        if not os.path.exists("./convert/output/sub"): 
            os.makedirs("./convert/output/sub") 
        if not os.path.exists("./convert/result"): 
            os.makedirs("./convert/result") 
        if not os.path.exists("./progress"): 
            os.makedirs("./progress") 

        os.rename(f'./on_hold/{now}', f"./convert/assets/{file}")
    else:
        exit()
else:
    exit()

#-----------------------------------------------------------------------------------------------------------
# Phase 2:
#  1 - extrait les informations du fichier mkv dans ./output/info.json

#-P2.1
def ffprobe(file_path):
    f = open("./convert/output/info.json", "w")
    command_array = ["ffprobe",
                     "-v", "quiet",
                     "-print_format", "json",
                     "-show_format",
                     "-show_streams",
                     file_path]
    result = subprocess.run(command_array, stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True)
    f.write(result.stdout)

ffprobe(f'./convert/assets/{file}')

with open('./convert/output/info.json') as f:
   data = json.load(f)

#------------------------ P2.2

# info_for_web = []

# subtitle = 0
# video = 0
# audio = 0

# for k in data['streams']:
#     if k['codec_type'] == "subtitle":
#         subtitle +=1
#         info_for_web.append([f"subtitle{subtitle}",0])

#     elif k['codec_type'] == "video":
#         video += 1
#         info_for_web.append([f"video{video}",0])

#     elif k['codec_type'] == "audio":
#         audio +=1
#         info_for_web.append([f"audio{audio}",0])

# json_list["info_for_web"] = info_for_web
# with open(f'waiting_list.json', 'w+') as outfile:
#     json.dump(json_list, outfile, indent = 4)

#---------------------------
#---------------------------
#---------------------------

srt_command = ["ffmpeg", "-i", "./convert/result/video1.mp4" ]
mid_command = ["-map","0:v"]
end_command = ["-c:v","copy" ,"-c:a" ,"aac" ,"-ac" ,"2" ,"-f","dash","./convert/output/video/output.mpd","-progress","/opt/vod.lgdl.org/video_convert/progress/final.txt"]

subtitle = 0
video = 0
audio = 0

for k in data['streams']:
    if k['codec_type'] == "subtitle" and k['codec_name'] != "hdmv_pgs_subtitle":
        subtitle +=1
        if k['tags']['title'] is not None:
            title = k['tags']['language'] + "_" + k['tags']['title']
        else:
            title = k['tags']['language'] + "_" + subtitle
        try:
            print("=========================================================================================================")
            print(f"./convert/output/sub/{title}")
            subprocess.run(["ffmpeg", "-stats", "-i", f"./convert/assets/{file}", "-map", f"0:{k['index']}", f"./convert/output/sub/{title}.vtt", "-y", "-progress" ,f"/opt/vod.lgdl.org/video_convert/progress/subtitle{subtitle}.txt"])
            # subprocess.run(["ffmpeg", "-v", "quiet", "-stats", "-i", f"./convert/assets/{file}", "-map", f"0:{k['index']}", f"./convert/output/sub/{title}.vtt", "-y", "-progress" ,f"/opt/vod.lgdl.org/video_convert/progress/subtitle{subtitle}.txt"])
            print("OK")
        except Exception as error:
            print("=========================================================================================================")
            print(f"subtitle {subtitle}")
            print("An exception occurred:", error)

    elif k['codec_type'] == "video":
        video += 1
        try:
            print("=========================================================================================================")
            print(f"./convert/result/video{video}.mp4")
            subprocess.run(["ffmpeg", "-stats", "-i", f"./convert/assets/{file}", "-map", f"0:{k['index']}", "-c:v", "libx264", "-profile:v", "high", "-level:v", "4", "-pix_fmt", "yuv420p", "-progress" ,f"/opt/vod.lgdl.org/video_convert/progress/video{video}.txt", "-f", "mp4", f"./convert/result/video{video}.mp4", "-y"])
            # subprocess.run(["ffmpeg", "-v", "quiet", "-stats", "-i", f"./convert/assets/{file}", "-map", f"0:{k['index']}", "-c:v", "libx264", "-profile:v", "high", "-level:v", "4", "-pix_fmt", "yuv420p", "-progress" ,f"/opt/vod.lgdl.org/video_convert/progress/video{video}.txt", "-f", "mp4", f"./convert/result/video{video}.mp4", "-y"])
            print("OK")
        except Exception as error:
            print("=========================================================================================================")
            print(f"video {video}")
            print("An exception occurred:", error)


    elif k['codec_type'] == "audio":
        audio +=1
        try:
            print("=========================================================================================================")
            print(f"./convert/result/audio{audio}.m4a")
            subprocess.run(["ffmpeg", "-stats", "-i", f"./convert/assets/{file}", "-c:a", "aac", "-map", f"0:{k['index']}", "-b:a" , "256000", f"./convert/result/audio{audio}.m4a", "-y", "-progress" ,f"/opt/vod.lgdl.org/video_convert/progress/audio{audio}.txt" , "-y"])
            # subprocess.run(["ffmpeg", "-v", "quiet", "-stats", "-i", f"./convert/assets/{file}", "-c:a", "aac", "-map", f"0:{k['index']}", "-b:a" , "256000", f"./convert/result/audio{audio}.m4a", "-y", "-progress" ,f"/opt/vod.lgdl.org/video_convert/progress/audio{audio}.txt" , "-y"])
            print("OK")
            srt_command.append("-i")
            srt_command.append(f"./convert/result/audio{audio}.m4a")
            mid_command.append("-map")
            mid_command.append(f"{audio}:a")
        except Exception as error:
            print("=========================================================================================================")
            print(f"audio {audio}")
            print("An exception occurred:", error)

command = srt_command + mid_command + end_command
command_humain_read = ""
for x in command:
    command_humain_read += x + " "

try:
    print("=========================================================================================================")
    print(command_humain_read)
    subprocess.run(command) # FINAL
    print("OK")

    directory = str(uuid.uuid4())[:8]
    os.makedirs(f"/opt/vod.lgdl.org/films/{directory}")

    os.rename(f'./convert/output/', f"/opt/vod.lgdl.org/films/{directory}")



    json_list = {'in_progress':0,'wait':waiting_list["wait"],'current':""}
    with open(f'waiting_list.json', 'w+') as outfile:
        json.dump(json_list, outfile, indent = 4)

    with open(f'/opt/vod.lgdl.org/films/films.json', 'r') as ff:
        films_list = json.load(ff)

    films_list[directory] = now
    with open(f'/opt/vod.lgdl.org/films/films.json', 'w+') as films_list_file:
        json.dump(films_list, films_list_file, indent = 4)




except Exception as error:
    print("=========================================================================================================")
    print(command_humain_read)
    print("An exception occurred:", error)

shutil.rmtree('./progress/')
shutil.rmtree('./convert/result/')
shutil.rmtree('./convert/assets/')

