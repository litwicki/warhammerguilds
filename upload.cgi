#!/usr/bin/perl -w
#
# use #!c:/perl/bin/Perl.exe for windows
# Fixed for windows by clem and modified...
#
#
# PHP File Uploader with progress bar Version 2.0
# Copyright (C) Raditha Dissanyake 2003
# http://www.raditha.com
# Changed for use with AJAX by Tomas Larsson
# http://tomas.epineer.se/

# Licence:
# The contents of this file are subject to the Mozilla Public
# License Version 1.1 (the "License"); you may not use this file
# except in compliance with the License. You may obtain a copy of
# the License at http://www.mozilla.org/MPL/
# 
# Software distributed under this License is distributed on an "AS
# IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
# implied. See the License for the specific language governing
# rights and limitations under the License.
# 
# The Initial Developer of the Original Code is Raditha Dissanayake.
# Portions created by Raditha are Copyright (C) 2003
# Raditha Dissanayake. All Rights Reserved.
# 

# CHANGES:
# As of version 1.00 cookies were abolished!
# as of version 1.02 stdin is no longer set to non blocking.
# 1.40 - POST is no longer required and processing is more efficient.
#	Please refer online docs  for details.
# 1.42 - The temporary locations were changed, to make it easier to
#	clean up afterwards.	
# 1.45.
#   Changed the way in which the file list is passed to the php handler
# 2.0  (2006-03-12) (Tomas Larsson)
#   Changed to work better with AJAX. This meant improved error handling
#	and no forwarding to php page after successful upload. Also moved settings
#	in to this file.
# 3.0 (REVISED 2006-04-27) (Jeremy Dill)
#   Fixed a few items that threw CGI notices such as 
#	vars defined for no apparent reason, and reopening of same files.
#	Added support for 3rd parameter to turn on debugging output.
#	Added support for 4th parameter and formula to crudely control upload speed range from 1-10.
#	Added support for 5th parameter to pass temp file location to script so that 
#	this cgi file doesn't need to be modified in order to implement.
#   Generally cleaned up formatting and added/changed comments.
#	USAGE: upload.cgi?sid=[unique id]&maxfile=[integer in bytes]&temp_dir=[full path to temp dir on server where uploaded files will be saved]&cgidebug=[1 or undef]&speed=[range between 1.0 and 10.0]
#   EXAMPLE:  <form id="file_upload" enctype="multipart/form-data" action="/cgi-bin/upload.cgi?sid=46f9fc94c4fad9139b038f0844bfbbcc&maxfile=52428800&cgidebug=1&speed=5&temp_dir=/var/www/files/uploadtmp/" method="post" target="destination">
#	NOTE: PARAMETERS ARE REQUIRED!
# 3.0.1 (REVISED 2007-03-19) (Jeremy Dill FROM Clem http://labs.beffa.org/w2box/)
# 		correct chmoding of the file and cleaning of temp file if the script is aborted

$high_max_upload = 209715200; # THIS WILL OVERRIDE PARAMETER PASSED TO SCRIPT IF VALUE IS LOWER
$default_speed=10; # THIS IS DEFAULT SPEED IF NONE IS SET.
$tmp_dir= "/home/thezdin/public_html/tmp_files/";# SET TEMP DIR MANUALLY IF YOU SET $tmpDirInCGI=1 IN THE PHP CONFIG FILE.

use CGI;
use Fcntl qw(:DEFAULT :flock);
use File::Temp qw/ tempfile tempdir /;

print "Content-type: text/html\n\n";
print "<html><body>";

# GET ALL PARAMETERS
@qstring=split(/&/,$ENV{'QUERY_STRING'});
@p1 = split(/=/,$qstring[0]);
@p2 = split(/=/,$qstring[1]);
@p3 = split(/=/,$qstring[2]);
@p4 = split(/=/,$qstring[3]);
@p5 = split(/=/,$qstring[4]);
$sessionid = $p1[1];
$sessionid =~ s/[^a-zA-Z0-9]//g;  # sanitized as suggested by Terrence Johnson.
if ($sessionid eq "undefined") {
		print "<script language=javascript>alert('Encountered error: Session was not defined.');</script></body></html>\n";
		exit;
	}
$max_upload = $p2[1];
$max_upload =~ s/[^0-9]//g;  # sanitized
if ($max_upload > $high_max_upload) {$max_upload = $high_max_upload;}
$debugger = $p3[1];
$speed = $p4[1];
if(not $tmp_dir) {$tmp_dir = $p5[1];}

unless (($speed >= 1) && ($speed <= 10)) {$speed=$default_speed;}

# FILES TO BE MONITORED BY PHP
$post_data_file = "$tmp_dir/$sessionid"."_postdata";
$monitor_file = "$tmp_dir/$sessionid"."_flength";
$error_file = "$tmp_dir/$sessionid"."_err";
$signal_file = "$tmp_dir/$sessionid"."_signal";
$qstring_file = "$tmp_dir/$sessionid"."_qstring";

#$content_type = $ENV{'CONTENT_TYPE'};  #not used.
$len = $ENV{'CONTENT_LENGTH'};
$bRead=0;
$|=1;

sub bye_bye {
	$mes = shift;
	# OPEN ERROR FILE IF NOT ALREADY OPEN
	unless (defined fileno ERRFILE) {
	$err_ok = open (ERRFILE,">", "$error_file");
	}
	if($err_ok){
		print ERRFILE $mes; #WRITE TO ERROR FILE FOR READING BY PHP
		close (ERRFILE);
	} else {
		# SINCE WE COULND'T MAKE AN ERROR FILE, SEND RESPONSE BACK TO BROWSER IN FORM OF AN ALERT.
		print "<script language=javascript>alert('Encountered error: $mes. Also unable to write to error file.');</script></body></html>\n";
	}
	exit;
}

# DELETE ERROR FILE IF IT EXISTS.  
if (-e $error_file) 
{ 
	unlink($error_file);
}

#SET LOOP DELAY
#NOTE: THE INSANE EQUATION CREATES A FUNCTIONAL THROTTLE DIFFERENTIAL.
$delay=1/(0.089514*$speed**5-1.5673433*$speed**4+10.5844136*$speed**3-33.1481061*$speed**2+47.4944435*$speed-22.5141751); 

# WRITE DEBUGGING INFO
if($debugger) {print "MAX Upload:$max_upload bytes <br />TMP DIR:$tmp_dir<br>SID:$sessionid<br />Speed Delay In Seconds:$delay<br />"};

#FILESIZE CHECK
if($len > $max_upload) 
{
	close (STDIN);
	bye_bye("The maximum upload size of $max_upload bytes has been exceeded");
}

# MAKE SURE FILES ARE NOT IN USE.
if (-e "$post_data_file") {
	unlink("$post_data_file");
}
if (-e "$monitor_file") {
	unlink("$monitor_file");
}

sysopen(FH, $monitor_file, O_RDWR | O_CREAT)
	or &bye_bye ("Unable to open numfile: $!");

# autoflush FH
$ofh = select(FH); $| = 1; select ($ofh);
flock(FH, LOCK_EX)
	or  &bye_bye ("Unable to write-lock numfile: $!");
seek(FH, 0, 0)
	or &bye_bye ("Unable to rewind numfile : $!");
print FH $len;	
close(FH);	
	
open(TMP,">","$post_data_file") or &bye_bye ("Unable to open temp file");
binmode TMP; 

#
# read and store the raw post data on a temporary file so that we can
# pass it though to a CGI instance later on.
#



my $i=0;

$ofh = select(TMP); $| = 1; select ($ofh);
while (read (STDIN ,$LINE, 4096) && $bRead < $len )
{
	$bRead += length $LINE;
	select(undef, undef, undef,$delay);	#THIS LIMITS THE UPLOAD SPEED BY CREATING A DELAY
										#THE 4TH PARAM INDICATES NUMBER OF SECONDS TO WAIT.

	$i++;
	if (-e $post_data_file) {
		print TMP $LINE;
	} else {
		if($debugger) {print "Upload Cancelled."};
		&bye_bye ("Temp file was deleted. Upload was cancelled?");
	}
}

close (TMP);

#
# We don't want to decode the post data ourselves. That's like
# reinventing the wheel. If we handle the post data with the perl
# CGI module that means the PHP script does not get access to the
# files, but there is a way around this.
#
# We can ask the CGI module to save the files, then we can pass
# these filenames to the PHP script. In other words instead of
# giving the raw post data (which contains the 'bodies' of the
# files), we just send a list of file names.
#

open(STDIN,"$post_data_file") or &bye_bye("Unable to open temp file");

my $cg = new CGI();
my $qstring="";
my %vars = $cg->Vars;
my $j=0;

while(($key,$value) = each %vars)
{
	if(defined $value && $value ne '')
	{	
		my $fh = $cg->upload($key);
		if(defined $fh)
		{
			($tmp_fh, $tmp_filename) = tempfile(DIR => $tmp_dir);
			binmode $tmp_fh;
			while(<$fh>) {
				print $tmp_fh $_;
			}
			$tmp_filenames[$j]=$tmp_filename;
			close($tmp_fh);
			chmod 0644, $tmp_filenames[$j];

			$fsize =(-s $fh);
			$fh =~ s/^.*[\/\\]//; # strip off any path information that IE puts in the filename
			$fh =~ s/([^a-zA-Z0-9_\-.])/uc sprintf("%%%02x",ord($1))/eg;
			$tmp_filename =~ s/([^a-zA-Z0-9_\-.])/uc sprintf("%%%02x",ord($1))/eg;
			$qstring .= "file[name][$j]=$fh&file[size][$j]=$fsize&";
			$qstring .= "file[tmp_name][$j]=$tmp_filename&";
			if($debugger) {print "File:$fh<br />"};
			$j++;
		}
		else
		{
			$value =~ s/([^a-zA-Z0-9_\-.])/uc sprintf("%%%02x",ord($1))/eg;
			$qstring .= "$key=$value&" ;
		}
	}
}

open (QSTR,">", "$qstring_file") or &bye_bye ("Unable to open output file");
print QSTR $qstring;
close (QSTR);

open (SIGNAL,">", $signal_file) or &bye_bye ("Unable to open signal file");
print SIGNAL "\n";
close (SIGNAL);

# SET PERMISSIONS ON CREATED FILES
chmod 0644, @tmp_filenames;
print "</body></html>";
END {
	#clean up the mess on abortion
	if (not -e $signal_file){
		if (-e $post_data_file){ unlink($post_data_file); }
		if (-e $monitor_file){ unlink($monitor_file); }
		if (-e $qstring_file){ unlink($qstring_file); }
		if (-e $tmp_filename){ unlink($tmp_filename); }
	}
}