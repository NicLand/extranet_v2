//Macro to create composite with wavelength choice version 9.0
//Nicolas LANDREIN
//=============== Variables=======================

listeMicroscope = newArray("AxioImager Z1","AxioPlan");
sourceType = newArray("UV Lamp","CoolLED");

nWavelengthDef = 3;
nColours = newArray("Far Red","Red","Green","Blue","Phase","DIC");
finalColour = newArray("Red","Green","Blue","Gray","Cyan","Magenta","Yellow");
finalC = newArray ("c1","c2","c3","c4","c5","c6","c7");

filtersZ1_UV = newArray ("Cy5","1DsRed","2FITC","3Dapi","0Phase Ph3","0DIC 100x");
filtersZ1_LED = newArray("Cy5 LED","1DsRed LED","2FITC LED","3Dapi LED","0Phase Ph3 TRIPLE");
filtersPlan_UV = newArray("","RED", "FITC", "DAPI","Phase");
filtersPlan_LED = newArray ("","LED RED","LED FITC","LED DAPI","Phase");

listeObjectif = newArray("100X", "63X", "40X");
objectifPixel = newArray(0.0645, 0.10238, 0,1615);
items = newArray("Average","Max","Min","Sum","Nothing");
first_image = 1;
last_image = 4;
scale_Size = 5;


// ============ Directory ==============
run ("Close All");
repertoire=getDirectory("Choose a Directory");

slashIndex = lastIndexOf(repertoire,"/");
firstSub = substring (repertoire, 0 ,slashIndex);
//print(firstSub);
slashIndexBis = lastIndexOf (firstSub,"/");
folderName = substring(firstSub, slashIndexBis);
NomDeBase = substring(folderName,1);

// ============Step 1 : Microsciope and Number of channels ==============
Dialog.create("Microscope / Wavelength");
Dialog.addMessage("Step 1/4");
Dialog.addMessage("Microscope and channels choices");
Dialog.addChoice("Microscope used :",listeMicroscope, listeMicroscope[0]);
Dialog.addChoice("UV source :", sourceType, sourceType[0]);
Dialog.addChoice("Objectif used :", listeObjectif, listeObjectif[0]);
Dialog.addNumber("Wavenlength Number :" nWavelengthDef);
Dialog.show();

microscope = Dialog.getChoice();
UVsource = Dialog.getChoice();
objectif = Dialog.getChoice();
nWavelength =Dialog.getNumber();

// =========== Step 2 : Colours choice ==================

Dialog.create("Colours");
Dialog.addMessage("Step 2/4");
Dialog.addMessage ("Filters used to acquire the images.");

for (i=0; i<nWavelength-2; i++)
{
Dialog.addChoice("Filter n°"+i+1,nColours, nColours[i]);
}
for (i=0; i<2; i++)
{
Dialog.addChoice("Filter n°"+i+nWavelength-1,nColours, nColours[i+3]);
}
Dialog.show();

colours = newArray(nWavelength);
for (j=0; j<nWavelength; j++)
{
colours[j] =Dialog.getChoice();
}
//printArray(colours);

//============= Step 2bis : Colours of the channel in the composite ================
Dialog.create("Colours into th e composite");
Dialog.addMessage("Step 2bis/4");
Dialog.addMessage("colours into the composite.");

for (z=0; z<nWavelength; z++)
{
	Dialog.addChoice("Colour for "+colours[z], finalColour);
}
Dialog.show();
finalColours = newArray(nWavelength);
for (y=0; y<nWavelength; y++)
{
	finalColours[y]= Dialog.getChoice();
}
//printArray(finalColours);

// ============ Step 3 : Stack or not stack ===============
Dialog.create ("Stack or not Stack");
Dialog.addMessage("Step 3/4");
Dialog.addMessage("Do you have stacks ? (Check means : Yes)");
for (k=0; k<nWavelength; k++)
{
Dialog.addCheckbox (colours[k],false)
}
Dialog.show();

stackOrNot = newArray(nWavelength);
for (l=0; l<nWavelength; l++)
{
stackOrNot[l] = Dialog.getCheckbox();
}

//printArray(stackOrNot);
// ==============Step 3bis : What to do with Stacks =========

for (i=0; i<nWavelength; i++){toStack += stackOrNot[i];}
if (toStack > 0)
{
	Dialog.create("What to do with Stacks");
	Dialog.addMessage("Step 3bis/4");
	Dialog.addMessage("Which Z projection do you want for each stack ?");
		for (i=0; i<nWavelength; i++)
		{
			if (stackOrNot[i] == 1)
				{
				Dialog.addRadioButtonGroup(colours[i], items, 1,  items.length, items[1] );
				}
		}
	Dialog.show();
}
stacking = newArray(nWavelength);
		for (i=0;i<nWavelength;i++)
			{
				if (stackOrNot[i] == 0){stacking[i] = items[4];}
				if (stackOrNot[i] == 1){stacking[i] = Dialog.getRadioButton();}
			}
			//printArray(stacking);
// ============= Suffixes Generator =========================
suff = newArray(nWavelength);
	if (microscope == listeMicroscope[0]){
		if (UVsource == sourceType[0]){ filters = filtersZ1_UV;}
		else if (UVsource == sourceType[1]){ filters = filtersZ1_LED;}
		}
	else if (microscope == listeMicroscope[1]){
		if (UVsource == sourceType[0]){ filters = filtersPlan_UV;}
		else if (UVsource == sourceType[1]){ filters = filtersPlan_LED;}
		}
for (m=0; m<nWavelength; m++)
{
	for (i=0; i<nColours.length; i++)
		{
		if (colours[m] == nColours[i])
			{
			suff[m] = "_w"+(m+1)+""+filters[i]+".";
			if (stackOrNot[m] == 0) {suff[m] = ""+suff[m]+"TIF";}
			else {suff[m] = suff[m]+"STK";}
			}
		else{}
		}
}	
// ============= Creer dossier de destination ===============
	
Dialog.create("Parameters");
Dialog.addMessage("Step 4/4");
Dialog.addMessage("Please check and fill the form to proceed to the auto composite !");
Dialog.addString("Scale Bar Unit :", "um");
Dialog.addNumber("Scale Bar Size :", scale_Size);
Dialog.addString("Basename :", NomDeBase, 20);
Dialog.addNumber("First image :", first_image);
Dialog.addNumber("Last image :", last_image);
for (i=0; i<nWavelength; i++)
{
	Dialog.addString("Channel n°"+(i+1), suff[i],20);
}
Dialog.addString("Directory's name :", "composite",20);
Dialog.show();

pixelUnit = Dialog.getString();
scaleSize = Dialog.getNumber();
nomB = Dialog.getString();
firstImage = Dialog.getNumber();
lastImage = Dialog.getNumber();
suffixe = newArray(nWavelength);
for (i=0; i<nWavelength; i++)
{
	suffixe[i] = Dialog.getString();
}
directoryName = Dialog.getString();
resultsDir =repertoire+File.separator+directoryName+File.separator;
	File.makeDirectory(resultsDir);

// ============= Boucle ========================
setBatchMode(true);
for (index=firstImage; index<=lastImage; index++)
{
	name = newArray(nWavelength);
	for (i=0; i<nWavelength; i++)
		{
			name[i] = nomB+index+""+suffixe[i];
		}
	//image treatment
		imageTreatment(repertoire, name, finalColour, finalColours, stacking);
	//Rename and save treated image
		rename(nomB+index+"_composite");
		saveAs("ZIP", resultsDir+nomB+index+"_composite.zip");
		close();
}
setBatchMode(false);
// ============= Functions =======================
function imageTreatment(rep, imageName, channel, color, stack) {
// Open images
printArray(imageName);
printArray(stack);

	for (i=0; i<imageName.length; i++)
		{
			open(rep+imageName[i]);
			if (stack[i] == items[1]){run ("Z Project...", "projection=[Max Intensity]"); close (imageName[i]); pref="MAX_";}
			else if (stack[i] == items[2]) {run ("Z Project...", "projection=[Min Intensity]"); close (imageName[i]);pref="MIN_";}
			else if (stack[i] == items[0]) {run ("Z Project...", "projection=[Average Intensity]");close (imageName[i]);pref="AVG_";}
			else if (stack[i] == items[3]) {run ("Z Project...", "projection=[Sum Slices]");close (imageName[i]);pref="SUM_";}
			else if (stack[i] == items[4]) {pref="";}
			else if (stack[i] == 0) {pref="";}
			else {pref="";}
// Merge channels	
			if (color[i] == channel[0]){mergeColor = ""+mergeColor +"c1=["+pref+imageName[i]+"]"; } //Red
			else if (color[i] == channel[1]){ mergeColor = ""+mergeColor + "c2=["+pref+imageName[i]+"]";} //Green
			else if (color[i] == channel[2]){ mergeColor = ""+mergeColor + "c3=["+pref+imageName[i]+"]";} //Blue
			else if (color[i] == channel[3]){ mergeColor = ""+mergeColor + "c4=["+pref+imageName[i]+"]";} //Gray
			else if (color[i] == channel[4]){ mergeColor = ""+mergeColor + "c5=["+pref+imageName[i]+"]";} //Cyan
			else if (color[i] == channel[5]){ mergeColor = ""+mergeColor + "c6=["+pref+imageName[i]+"]";} //Magenta
			else if (color[i] == channel[6]){ mergeColor = ""+mergeColor + "c7=["+pref+imageName[i]+"]";} //Yellow
		}
			run ("Merge Channels...", " "+mergeColor+" create");
//Calibrate the image
			for (i=0; i<listeObjectif.length; i++)
			{
				if (objectif == listeObjectif[i]){Pixel = objectifPixel[i];}
			}
			run("Properties...", "frames=1 unit="+pixelUnit+" pixel_width="+Pixel+" pixel_height="+Pixel+" voxel_depth="+Pixel);
//Scale Bar
			//Add a channel for the scale bar
			run("Add Slice", "add=channel prepend");
//Add the scale bar on the first channel (gray).
			Stack.setChannel(1);
			run("Scale Bar...", "width="+scaleSize+" height=8 font=28 color=White background=None location=[Lower Right] bold");
}
//====================================================

function printArray(a) {
      print("");
      for (i=0; i<a.length; i++)
          print(i+": "+a[i]);
  }

