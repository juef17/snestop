#place all rsn files in rsn folder
#spcs will be extracted in tmp folder then moved
#download an up to date version of the spcid666.py file at:
#  https://github.com/Jerther/py_spcID666

from __future__ import division
import spcid666
import glob
import rarfile
import hashlib
import shutil
import os
import re
import io

rsnfolder = 'rsn/'
tmpfolder = 'tmp/'
spcfolder = 'spc/'

total = 0
fsql = io.open('gametrack.sql','w', encoding='utf8')

def _is_sound_effect(spcFileName):
	return _get_modifier(spcFileName) in ['s', 'se', 'fx']

def _is_jingle(spcFileName):
	return _get_modifier(spcFileName) == 'j'

def _is_voice(spcFileName):
	return _get_modifier(spcFileName) == 'v'

def _get_modifier(spcFileName):
	dashPosition = spcFileName.find('-')
	if dashPosition > 0:
		modifier = spcFileName[dashPosition + 1:]
		match = re.search("\d|-", modifier)
		modifier = modifier[:match.start()]
		return modifier
	else:
		return None

for f in glob.glob(rsnfolder + "*.rsn"):
	total += 1

	try:
		rf = rarfile.RarFile(f)
	except:
		print f
		raise
	
	rsnFile = f[len(rsnfolder):]
	for r in [r for r in rf.infolist() if r.filename[-4:] == '.spc']:
		rf.extract(r, tmpfolder)

	firstTag = spcid666.parse(glob.glob(tmpfolder + "*.spc")[0])
	gameTitleEng = firstTag.extended.game or firstTag.base.game
	query = u"INSERT Game (titleEng, titleJap, rsnFileUrl) VALUES ('{0}', '', '{1}');\n".format(gameTitleEng.replace("'", "''"), rsnFile.replace("'", "''"))
	fsql.write(query)
	fsql.write(u'set @lastid = LAST_INSERT_ID();\n')
	print total, rsnFile

	trackNumber = 0
	for sf in sorted(glob.glob(tmpfolder + "*.spc")):
		trackNumber += 1
		spcFileName = sf[len(tmpfolder):]
		
		tag = spcid666.parse(sf)
		length = tag.extended.intro_length / 64000 if tag.extended.intro_length != None else tag.base.length_before_fadeout
		fadeoutLength = tag.extended.fade_length / 64000 if tag.extended.fade_length != None else tag.base.fadeout_length
		isSoundEffect = _is_sound_effect(spcFileName)
		isVoice = _is_voice(spcFileName)
		isJingle = not isVoice and not isSoundEffect and (_is_jingle(spcFileName) or length <= 20)

		fsql.write(u"INSERT Track (idGame, title, length, fadeLength, composer, isJingle, spcURL, isSoundEffect, isVoice, trackNumber) VALUES (@lastid, '{0}', {1}, {2}, '{3}', {4}, '{5}', {6}, {7}, {8});\n".format(
			(tag.extended.title or tag.base.title).replace("'", "''"),
			length,
			fadeoutLength,
			(tag.extended.artist or tag.base.artist)[:150].replace("'", "''"),
			1 if isJingle else 0,
			spcFileName,
			1 if isSoundEffect else 0,
			1 if isVoice else 0,
			trackNumber
		))

		shutil.move(sf, spcfolder)
