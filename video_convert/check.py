import os
import json
import unidecode
import urllib.request
import subprocess

#--------------------------------------------------------------------------------------------

with open(f'waiting_list.json', 'r') as f:
    data = json.load(f)

if data["in_progress"] == 1:
    # si une conversion est déjà en cours, ne rien faire
    exit()

#--------------------------------------------------------------------------------------------

path = "on_hold"

for root, dirpath, file_names in os.walk(path, topdown=False):
    for fname in file_names:
        if fname in data["wait"]:
            pass
        else:
            wait = data["wait"]
            wait.append(fname)
            json_list = {'in_progress':0,'wait':wait}
            with open(f'waiting_list.json', 'w+') as outfile:
                json.dump(json_list, outfile, indent = 4)

subprocess.run(["python3", "./convert/script.py"])

