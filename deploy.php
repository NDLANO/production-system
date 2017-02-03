<?php
if (count($argv) < 2) {
    echo "Usage: $argv[0] <description>";
    exit(1);
}

$description = join(" ", array_slice($argv, 1));

$deployment_group = "NDLA_Produksjonssystem";
$deployment_group_placeholder = "<deployment-group-name>";

$deployment_config_placeholder = "<deployment-config-name>";
$deployment_config = "CodeDeployDefault.OneAtATime";

$description_placeholder = "<description>";

$push_cmd = "aws deploy push --application-name NDLA_Produksjonssystem --s3-location s3://ndla-produksjonssystem-codedeploy/codedeploy.zip --source wp-content";

$deploy_cmd = shell_exec($push_cmd);
echo "$deploy_cmd\n";
$deploy_cmd = substr($deploy_cmd, strpos($deploy_cmd, "aws"));
echo "$deploy_cmd\n";

$deploy_cmd = str_replace($deployment_group_placeholder, $deployment_group, $deploy_cmd);
echo "$deploy_cmd\n";
$deploy_cmd = str_replace($deployment_config_placeholder, $deployment_config, $deploy_cmd);
echo "$deploy_cmd\n";
$deploy_cmd = str_replace($description_placeholder, $description, $deploy_cmd);


echo $deploy_cmd;

	
