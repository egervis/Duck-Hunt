function getRandomInteger(lower, upper)
{
	if (lower > upper)
	{
		return null;
	}

	var multiplier = upper - lower + 1;
		
	var rnd = parseInt(Math.random() * multiplier) + lower;
	
	return rnd;
}

function getSuffix(number)
{
	if(number%10==3)
	{
		return "th";
	}
	if(number%10==2)
	{
		return "nd";
	}
	if(number%10==3)
	{
		return "st";
	}
}

function determine_even_or_odd(number)
{
	if(number%2==0)
		return true;//even
	else 
		return false;//odd
}

function stringContains(str, input)
{
	if(str.toUpperCase().indexOf(input.toUpperCase()) == -1)
		return false;
	else
		return true;
}